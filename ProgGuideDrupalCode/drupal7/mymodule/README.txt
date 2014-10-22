This directory contains the Drupal module 7 code from the book "Programmers
Guide to Drupal", by Jennifer Hodgdon, second edition, 2014.

License: See book for details of license and copyright.

Dependencies:

Some of the code in the sample module depends on the contributed Entity API
module, so you cannot enable this module without it. Some of the other code is
for integration with Rules, Panels, and CTools, but as it doesn't run without
those modules installed, they are not listed as dependencies of this
module. Modules mentioned:
- https://drupal.org/project/ctools
- https://drupal.org/project/entity
- https://drupal.org/project/panels
- https://drupal.org/project/rules

Contents of this directory:

- mymodule.module: Module file containing code from many sections of the book.
- mymodule.info: Info file for module.
- mymodule.install: Install hooks for module, from several sections of the book.

- mymodule.js: Empty JavaScript file for inclusion in a form array.

- mymodule.tpl.php: Theme template file from section "Making Your Output
  Themeable", Chapter 2.

- mymodule.rules.inc: Rules action from section "Providing Custom Actions to
  Rules", chapter 4.
- mymodule.rules_defaults.inc: Default rule provision from section "Providing
  Default Reaction Rules and Components", chapter 4.
- rules/sample_rule.txt: Exported sample rule for default rule provision.

- plugins/ctools-relationships/mymodule_relationship_most_recent_content.inc:
  CTools relationship plugin from "Implementing CTools Plugins in Drupal 7",
  chapter 4.

- tests/mymodule.test: SimpleTest test classes for code from the book.

Note: There is some functionality in the sample code that involves
JavaScript, and therefore cannot be tested within the SimpleTest framework.
To test it manually, you'll need to put this module in your sites/all/modules
directory, enable the "My Module" module, and do the following:

1. On the Personal Data Form page at example.com/mymodule/my_form_page :
   a. When you visit the page, there should be a JavaScript alert box saying
      "Hello!".
   b. The field labeled "Autocomplete field" should have auto-complete behavior.
      It is set up with a proxy that just adds some fixed text after
      whatever you type in, so you should see some choices. You will need
      to have 'Use company field' permission to see the auto-complete.
   c. If you type in the field labeled "Type here to trigger Ajax", you should
      see a message saying "You have triggered Ajax", and just below that, a
      line saying "You typed [whatever you typed]".
   d. If you click the button that says "Click here to trigger Ajax", you
      should see a message saying "The button has been clicked", with a
      light green background.
