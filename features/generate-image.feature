Feature: Images can be generated according to given criteria and added to the media library.

  Scenario: Generates 100 images by default.
    Given a WP install
    When I run `wp media generate image`
    Then STDOUT should contain:
      """
      Generated 100 images
      """

  Scenario: The number of images generated can be specified with a flag.
    Given a WP install
    When I run `wp media generate image --count=9`
    Then STDOUT should contain:
      """
      Generated 9 images
      """

  Scenario: Generated images are added to the media library.
    Given a WP install
    And the media library should contain 0 items

    When I run `wp media generate image --count=10`
    Then the media library should contain 10 items

  Scenario: The default generated size for each image is small enough to only create a single file per item.
    Given a WP install
    When I run `wp media generate image --count=1`
    And the uploads directory should contain 1 file

  Scenario: Images can be generated with specific dimensions.
    Given a WP install
    When I run `wp media generate image --count=1 --size=123x45`
    Then the uploads directory should contain 1 image 123px wide and 45px tall

  Scenario: Images can be generated with a given file extension.
    Given a WP install
    When I run `wp media generate image --count=1 --ext=gif`
    Then the uploads directory should contain 1 gif
