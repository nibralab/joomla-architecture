---
layout: default
title: Command Structure
category: Controller
author: Niels Braczek
author_email: nbraczek@bsds.de
---

## Command Structure

**Commands** are going to replace the controllers of Joomla! < 4.0.
Each command implements a single task, that can be addressed from arbitrary channels.

A generic **Controller** will gather the available commands for a given domain (usually a component),
identify the requested command, and execute it.

![UML Class Diagram](https://github.com/nibralab/joomla-architecture/blob/master/svg/class-command.svg)
