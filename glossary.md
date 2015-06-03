---
layout: default
title: Glossary
category: Appendix
author: Niels Braczek
author_email: nbraczek@bsds.de
---

## Glossar

### Command

**Command**s are going to replace the controllers of Joomla! < 4.0.
Each **Command** implements a single task, that can be addressed from arbitrary channels.

### CommandProcessor

The **CommandProcessor** will handle the execution of the command.
It triggers the `beforeExecute` and the `afterExecute` event,

### Controller

The **Controller** determines, which command has to be run, and executes it through a **CommandProcessor**.

### Input

The **Input** object is a channel independent encapsulation of the input data.

### Model

A **Model** contains the business (domain) logic.

### Output

The **Output** object is a channel independent encapsulation of the output data.
