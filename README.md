# Labs Services

Just another Wordpress plugin

## Features

### Elementor Accessibility

#### Issues

- An element with a role that hides child elements contains focusable child elements. This role element marks child elements as presentational, which hides them from the accessibility tree, but some of these children are focusable, so they can be navigated to, but are not voiced in a screen reader.

	 Solved on (with [Elementor Hook](https://code.elementor.com/php-hooks/ "Elementor Hook"))

		- Accordion
		- Tabs
		- Toggle



- An element with aria-hidden=true contains focusable content.  The hidden element and its contents are not voiced by a screen reader, but the contents contain an element with tabindex set or active a, button, input, select and textarea controls. The user can tab to these or focus via touch and hears only silence.

	 Solved on (with Javacript)

		- Nav Menu
		- Anchor Link



