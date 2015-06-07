---
layout: default
title: Command Line Interface
category: Frontend
author: Niels Braczek
author_email: nbraczek@bsds.de
---

## Command Line Interface

The Joomla Command Line Interface consists of the command `joomla` (or, in its long form, `php joomla.php`).

Each component by default provides one base command, which is the singular component name without the 'com_' prefix.

    # Get help for the user related commands 
    $ joomla user --help

    # Unpublish an article
    $ joomla content unpublish --filter="id=42"
    
