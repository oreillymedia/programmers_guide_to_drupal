This directory contains the Drupal module 8 code from the book "Programmers
Guide to Drupal", by Jennifer Hodgdon, second edition, 2014.

License: See book for details of license and copyright.

Contents:

- mymodule.module: Module file containing code from several sections.
- mymodule.info.yml: Info file for module.
- mymodule.install: Install hooks for module, from
  "Setting up Database Tables" section, chapter 2.

- mymodule.js: Empty JavaScript file for inclusion in a form array.

- mymodule.routing.yml: Routing information for module from several sections.
- mymodule.links.action.yml: Administrative action links for module, from
  several sections.
- mymodule.links.menu.yml: Administrative menu links for module, from several
  sections.
- mymodule.links.task.yml: Administrative task links for module, from several
  sections.
- mymodule.services.yml: Services provided by module, from "Event
  Subscribers in Drupal 8: Altering Routes and Providing Dynamic Routes",
  chapter 4.
- mymodule.permissions.yml: Permissions for module, from several sections.

- config/install/mymodule.settings.yml: Config file from section
  "Config API in Drupal 8", chapter 2.
- config/schema/mymodule.schema.yml: Config schema from several sections.

- src/Controller/MyUrlController.php: Route controller providing page output,
  from several sections.

- src/Entity/MyEntity.php: Entity class from "Defining a Content Entity Type in
  Drupal 8", chapter 4.
- src/Entity/MyEntityInterface.php: Entity interface from "Defining a Content
  Entity Type in Drupal 8", chapter 4.
- src/Entity/MyEntityForm.php: Entity editing form from "Defining a Content
  Entity Type in Drupal 8", chapter 4.
- src/Entity/MyEntityDeleteForm.php: Entity delete confirm form from "Defining a
  Content Entity Type in Drupal 8", chapter 4.

- src/Entity/MyEntityType.php: Entity class from "Defining a Configuration
  Entity Type in Drupal 8", chapter 4.
- src/Entity/MyEntityTypeInterface.php: Entity interface from "Defining a
  Configuration Entity Type in Drupal 8", chapter 4.
- src/Entity/MyEntityTypeForm.php: Entity subtype editing form from "Defining a
  Configuration Entity Type in Drupal 8", chapter 4.
- src/Entity/MyEntityTypeDeleteForm.php: Entity subtype delete confirm form
  from "Defining a Configuration Entity Type in Drupal 8", chapter 4.
- src/Entity/MyEntityListBuilder.php: Entity subtype admin page builder from
  "Defining a Configuration Entity Type in Drupal 8", chapter 4.

- src/Form/ConfirmDeleteForm.php: Delete confirm form class from "Creating
  Confirmation Forms", chapter 4.
- src/Form/PersonalDataForm.php: Personal data form class, from several
  sections.

- src/Plugin/Block/MyModuleFirstBlock.php: Block plugin from "Registering a
  Block in Drupal 8", chapter 4.

- src/Plugin/Field/FieldWidget/MyCustomText.php: Field widget from
  "Defining a field widget in Drupal 8", chapter 4.
- src/Plugin/Field/FieldFormatter/MyCustomText.php: Field widget from
  "Defining a field formatter in Drupal 8", chapter 4.

- src/Routing/MyModuleRouting.php: Event subscriber class from "Event
  Subscribers in Drupal 8: Altering Routes and Providing Dynamic Routes",
  chapter 4.

- src/Tests/*.php: SimpleTest tests for most of the code.

- templates/mymodule.html.twig: Theme template file from section "Making Your
  Output Themeable", chapter 2.
- templates/myentity.html.twig: Theme template file from section "Defining a
  Content Entity Type in Drupal 8", chapter 4.

Note: There is some functionality in the sample code that involves
JavaScript, and therefore cannot be tested within the SimpleTest framework.
To test it manually, you'll need to put this module in your modules
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
