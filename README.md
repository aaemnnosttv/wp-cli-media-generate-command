aaemnnosttv/wp-cli-media-generate-command
=========================================



[![Build Status](https://travis-ci.org/aaemnnosttv/wp-cli-media-generate-command.svg?branch=master)](https://travis-ci.org/aaemnnosttv/wp-cli-media-generate-command)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using

~~~
wp media generate image [--count=<number>] [--size=<size>] [--ext=<extension>] [--format=<format>]
~~~

	[--count=<number>]
		Number of images to generate.
		---
		default: 100
		---

	[--size=<size>]
		Image dimensions in LxW format.
		---
		default: 50x50
		---

	[--ext=<extension>]
		File extension to generate images with.
		---
		default: jpg
		---

	[--format=<format>]
		Render output in a particular format.
		---
		default: progress
		options:
		  - progress
		  - ids
		---

## Installing

Installing this package requires WP-CLI v1.0.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with:

    wp package install git@github.com:aaemnnosttv/wp-cli-media-generate-command.git

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

For a more thorough introduction, [check out WP-CLI's guide to contributing](https://make.wordpress.org/cli/handbook/contributing/).

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/aaemnnosttv/wp-cli-media-generate-command/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/aaemnnosttv/wp-cli-media-generate-command/issues/new). Include as much detail as you can, and clear steps to reproduce if possible. For more guidance, [review our bug report documentation](https://make.wordpress.org/cli/handbook/bug-reports/).

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/aaemnnosttv/wp-cli-media-generate-command/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, [please follow our guidelines for creating a pull request](https://make.wordpress.org/cli/handbook/pull-requests/) to make sure it's a pleasant experience.


*This README.md is generated dynamically from the project's codebase using `wp scaffold package-readme` ([doc](https://github.com/wp-cli/scaffold-package-command#wp-scaffold-package-readme)). To suggest changes, please submit a pull request against the corresponding part of the codebase.*
