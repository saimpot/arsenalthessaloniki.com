<?php
include 'FootballData.php';

const WP_USE_THEMES = false;
const APP_REQUEST   = true;        // This is needed for disabled W3 Total Cache


$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
require_once($path.'wp-load.php');
require_once($path.'wp-admin/includes/admin.php');

if ( ! function_exists( 'wp' ) ) {
	die( 'Sorry, looks like WordPress isn\'t loaded.' );
}

function logMessage( $message ) {
	echo $message . PHP_EOL;
}

/**
 * @param int $postId
 * @param string $mediaUrl
 *
 * @return string
 */
function _import_photo( int $postId, string $mediaUrl ): string {
	$image = '';
	$imageAlreadyExists = get_the_post_thumbnail_url($postId);

	if($imageAlreadyExists) {
		logMessage('    |__Image already exists. Skipping...');
		return '';
	}

	logMessage('    |__Uploading...');


	if($mediaUrl !== '') {
		$file = [];
		$file['name'] = $mediaUrl;
		$file['tmp_name'] = download_url($mediaUrl);

		if (is_wp_error($file['tmp_name'])) {
			@unlink($file['tmp_name']);
			var_dump( $file['tmp_name']->get_error_messages( ) );
		} else {
			$attachmentId = media_handle_sideload($file, $postId);

			if ( is_wp_error($attachmentId) ) {
				@unlink($file['tmp_name']);
				var_dump( $attachmentId->get_error_messages( ) );
			} else {
				$image = wp_get_attachment_image_url( $attachmentId );
			}
		}
	}

	// after all is said and done, insert the attachment to the relevant post
	set_post_thumbnail( $postId, $attachmentId );
	logMessage('    |__Image uploaded and set to the proper post!');
	return $image;
}

/**
 * @throws JsonException
 */
function sync_offers() {
	$api = new FootballData();
	$premierLeagueCompetitionId = 2021;

	logMessage( 'Getting teams from Premier League API...' );
	logMessage( '' );

	$standings = $api->findStandingsByCompetition($premierLeagueCompetitionId);

	if ( ! empty( $standings ) ) {
		foreach ( $standings['standings'][0] as $teams ) {
			foreach($teams as $team) {
				logMessage( '|__Processing team: ' . $team['team']['name'] . ' with ID #' . $team['team']['id'] );

				// Check if there is already a room with this name, since rooms from webhotelier don't have IDs
				$args = array(
					'post_type'      => 'team',
					'posts_per_page' => - 1,
					'hide_empty'     => true,
					'meta_query'     => array(
						array(
							'key'   => 'team_external_id',
							'value' => $team['team']['id']
						)
					)
				);

				$teams_query = new WP_Query( $args );
				$team_object  = array();
				while ( $teams_query->have_posts() ) {
					$teams_query->the_post();
					$team_object['ID'] = get_the_ID();
				}

				// Set the offer fields
				$team_object['post_type']    = 'team';
				$team_object['post_title']   = $team['team']['name'];
				$team_object['post_status']  = 'publish';

				if ( isset( $team_object['ID'] ) ) {
					logMessage( '  |__Team already in our database, updating...' );
					wp_update_post( $team_object );

					// Update the adult capacity
					// These should probably moved out of this if else since they're duplicated
					update_post_meta( $team_object['ID'], 'team_name', $team['team']['name'] );
					update_post_meta( $team_object['ID'], 'crest_url', $team['team']['crestUrl']);
					update_post_meta( $team_object['ID'], 'league_position', $team['position']);
					update_post_meta( $team_object['ID'], 'played_games', $team['playedGames']);
					update_post_meta( $team_object['ID'], 'won_games', $team['won']);
					update_post_meta( $team_object['ID'], 'drawn_games', $team['draw']);
					update_post_meta( $team_object['ID'], 'lost_games', $team['lost']);
					update_post_meta( $team_object['ID'], 'goal_difference', $team['goalDifference']);
				}
				else {
					logMessage( '  |__Inserting new team' );
					$postId       = wp_insert_post( $team_object );
					$team_object['ID'] = $postId;

					// Update the adult capacity
					// These should probably moved out of this if else since they're duplicated
					update_post_meta( $team_object['ID'], 'team_external_id', $team['team']['id'] );
					update_post_meta( $team_object['ID'], 'team_name', $team['team']['name'] );
					update_post_meta( $team_object['ID'], 'crest_url', $team['team']['crestUrl']);
					update_post_meta( $team_object['ID'], 'league_position', $team['position']);
					update_post_meta( $team_object['ID'], 'played_games', $team['playedGames']);
					update_post_meta( $team_object['ID'], 'won_games', $team['won']);
					update_post_meta( $team_object['ID'], 'drawn_games', $team['draw']);
					update_post_meta( $team_object['ID'], 'lost_games', $team['lost']);
					update_post_meta( $team_object['ID'], 'goal_difference', $team['goalDifference']);
				}

				if($team['team']['crestUrl'] !== '') {
					$photoUrl = _import_photo($team_object['ID'], $team['team']['crestUrl']);
				}

				wp_reset_postdata();
				wp_reset_query();
			}
		}

		logMessage( '' );
		logMessage( 'Finished importing teams!' );
	}

	wp_reset_postdata();
	wp_reset_query();

	logMessage( '' );
	logMessage( 'Premier League Sync finished!' );
}

/**
 * @throws JsonException
 */
function main() {
	if ( is_multisite() && function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
		logMessage( 'This is a multisite installation, iterating over all sites' );
		$sites = get_sites();
		foreach ( $sites as $site ) {
			$details = get_blog_details( array( 'blog_id' => $site->blog_id ) );
			logMessage( 'Switching to ' . $details->blogname . ' (' . $site->domain . ')' );
			switch_to_blog( $site->blog_id );
			sync_offers();
		}
		logMessage( 'Finished' );
		restore_current_blog();
	} else {
		sync_offers();
	}
}

main();