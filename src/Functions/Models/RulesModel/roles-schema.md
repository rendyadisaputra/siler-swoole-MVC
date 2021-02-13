# Roles : Auth_roles

---

**Table Name** : Auth_roles

---

**Attributes / columns**
+ *name      -> *unique | string*
+ *description -> *String*
+ related_rules_group -> *Array | list of rules of group*

---

**RolesModel :: Class**
+ createRoles( array: $data )
+ findByUniqueName( string: $roleName )