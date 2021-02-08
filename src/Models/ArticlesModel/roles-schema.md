# Articles : Auth_Articles

---

**Table Name** : Auth_Articles

---

**Attributes / columns**
+ *name      -> *unique | string*
+ *description -> *String*
+ related_rules_group -> *Array | list of rules of group*

---

**ArticlesModel :: Class**
+ createArticles( array: $data )
+ findByUniqueName( string: $roleName )