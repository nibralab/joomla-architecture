# Joomla Architecture

This is a pool of ideas for the architecture of Joomla! 4.

Concepts, that do not break compatibility to 3.x, may get implemented to 3.6.

## Content

### `master` Branch

The content consists of a couple of UML diagrams outlining basic concepts,
written in [PlantUML](http://plantuml.sourceforge.net/index.html)'s diagram scripting language.
The sources are found in the `src` directory.
The `svg` directory contains the rendered diagrams. They should be up to date with the sources, but that can not be
guaranteed.

### `gh-pages` Branch

This branch contains what is rendered on the [Joomla Architecture](http://nibralab.github.io/joomla-architecture/) site.
  
## Contribute

The [Joomla Architecture](http://nibralab.github.io/joomla-architecture/) website uses
[Jekyll](https://github.com/mojombo/jekyll/wiki), a simple yet powerful blog engine that easily allows for creating 
simple websites like these.

Contributing to the site is simple:

  * Fork the website to your own github account.
  * Clone your repository onto your computer. Make sure you create a new branch, and not use the `master` or `gh-pages` branch.
  * Create a new page inside the `_posts/` directory. This MUST be in the format of `year-month-day-filename.html`.
  * Add and commit your changes, and push it to your repository.
  * Create a pull request and we will try and merge your contribution.

### Creating a new page

As said, a page must be placed in the `_posts/` directory, and in the `year-month-day-filename.html` format.
Otherwise it won't get picked up by the system. Every post should look somewhat like this:

    ---
    layout: default
    title: <Title>
    category: <Category>
    author: <your name>
    author_email: <your email address>
    ---
    <h2>Title</h2>

    <p>Your text</p>

Your email address is optional. Make sure the category name is one of the existing category names (case matters),
otherwise your posting will be added inside a new topic. If you have troubles creating a new page, take a look at one
of the existing pages, or ask us for help.
