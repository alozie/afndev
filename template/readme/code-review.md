# Code Review

"How do I perform a code review?"

This document provides guidance for performing a review of another developer's
code. This should occur on GitHub via a Pull Request. See [dev-workflow.md]
(dev-workflow.md) for information on how to submit Pull Requests, and how they 
fit into the development workflow.

> "You just review the code right?" - Tim Holt

No.

@todo Document how to perform a code review, including:

* Use of global $language, LANGUAGE_NONE instead of 'und'
* Use of t()
* Caution with `hook_init()` and `hook_boot()`
* Caution with using `$_SESSION`
* Caution with full node/entity loads, particularly in loops
    * Use of Entity API, in particular `entity_metadata_wrapper()` as a way to 
      access and traverse entity properties and fields. Make sure to wrap usages 
      in `try { ... } catch (EntityMetadataWrapperException $e) { ... }`
* Verify [best practices](best-practices.md) are being used:
    * Views
    * Features 
    * Configuration updates 
