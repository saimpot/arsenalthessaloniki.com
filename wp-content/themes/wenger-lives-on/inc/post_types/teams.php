<?php
function team_content_types() {
	$args = [
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'exclude_from_search' => true
	];

	$book = register_cuztom_post_type( 'Team', $args );

	$metaBoxArgs = [
		'fields' => [
			[
				'id'                    => '_data_textarea_teams',
				'type'                  => 'textarea',
				'label'                 => 'Textarea label'
			]
		]
	];

	$book->addMetaBox('_team_meta_box', $metaBoxArgs);
}

add_action( 'init', 'team_content_types' );