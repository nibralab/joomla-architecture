---
layout: default
title: Undo Command Example
category: Controller
author: Niels Braczek
author_email: nbraczek@bsds.de
---

With the new architecture, it is possible to provide a global undo mechanism.
 
Since the execution of any **Command** is surrounded by the events `beforeExecute` and `afterExecute`, a simple **Plugin**
will do the job:

![UML Class Diagram](svg/class-undo-plugin.svg)

The **Plugin** manages an undo and a redo stack to store the **Command**s.
If it encounters a **Command**, that is not a **RecoverableCommand**, the undo stack is cleared.
 
In order to perform an **Undo** or a **Redo**, two **Command**s are needed:

![UML Sequence Diagram](svg/class-undo-command.svg)

