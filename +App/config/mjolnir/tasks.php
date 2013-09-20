<?php return array
	(
		/* Things to know about task configs...
		 *
		 *  - if a flag is not mentioned for the command it won't be passed
		 *  - configuration doubles as documentation
		 *  - A null value for a flag's default means it's mandatory.
		 *  - A non-null value means it's optional
		 *  - A false value means it's optional, but has no actual default value
		 *  - "toggle" is a special type for boolean flags, no need to pass value
		 *  - all "toggle" should have a default of false; using the flag => true
		 *  - if you do not specify a type, "toggle" is assumed
		 *  - if you do not specify a default, false is assumed
		 *  - each entry in the array of the command's description is a paragraph
		 *  - first entry in a command's description should be the oneline description
		 *  - flag types can be methods in any class; preferably the Task_Class itself
		 *  - you'll find general purpose tags in the Flags class
		 *
		 * If you need a command along the lines of:
		 *
		 *    minion some:command "something"
		 *    (meaning no flags)
		 *
		 * Just don't give it flags, handle it in the command's execution and explain it
		 * in the command's documentation (ie. description). Preferably use flags though
		 * and/or have that only as a shorthand and not as the only way.
		 */

		'theme:packager' => array
			(
				'category' => 'Tools',
				'description' => array
					(
						'Package theme files for production.',
						'Make sure you have your files compiled before running.'
					),
				'flags' => array
					(
						'local' => array
							(
								'description' => 'Only check DOCROOT/themes',
								'short' => 'l',
							),
					),
			),

		'make:theme' => array
			(
				'category' => 'Tools',
				'description' => array
					(
						'Create a basic theme.'
					),
				'flags' => array
					(
						'path' => array
							(
								'description' => 'Path of theme',
								'type' => 'text',
								'short' => 'p',
							),
						'forced' => array
							(
								'description' => 'Force file overwrites.'
							),
					),
			),

		'make:style' => array
			(
				'category' => 'Tools',
				'description' => array
					(
						'Create a basic theme style.'
					),
				'flags' => array
					(
						'path' => array
							(
								'description' => 'Path of theme',
								'type' => 'text',
								'short' => 'p',
							),
						'forced' => array
							(
								'description' => 'Force file overwrites.'
							),
					),
			),

	); # config
