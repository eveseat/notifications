# Notification Text Samples
When working on notifications, it is, like with any code, important to test your changes.
This poses some unique challenges with the `notifications` packages: Some notifications
like `StructureDestroyed` are hard to generate in EVE for obvious reasons, meaning it is 
difficult to test them. Therefore, we decided to create a collection of such difficult
notifications that can be imported for testing.

EVE notification in SeAT are stored in the `character_notifications` table. Most of the columns
of this table are easy to fake for testing, except for `type` and `text`, which aren't documented
by CCP.

The `notification-text-samples` directory contains a file for some notification types, with the
filename being the `type` column. In some cases, a comment is added with a `-`, e.g. 
`StructureUnderAttack-Hull.yaml`. In that case, only `StructureUnderAttack` is the type and
`Hull` is just a comment.

The content of such a file is the value of the `text` field. The data is randomized on request
of the people that helped to collect the dataset, so while it references for example valid 
structure ids, the solar system id might not match the actual system of the structure.