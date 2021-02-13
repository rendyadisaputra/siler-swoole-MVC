# EmailTemplates : Auth_EmailTemplates

---

**Table Name** : Auth_EmailTemplates

---

**Attributes / columns**
+ *name      -> *unique | string*
+ *description -> *String*
+ related_rules_group -> *Array | list of rules of group*

---

**EmailTemplatesModel :: Class**
+ createEmailTemplates( array: $data )
+ findByUniqueName( string: $roleName )