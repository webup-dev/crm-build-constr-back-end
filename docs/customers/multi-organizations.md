# Customers. Multi organizations
Customer may belongs to multiple organizations that have one parent on any level except level 1 (Platform).

To adapt current structure to the new:
* Create pivot table customer <---> organizations (many-to-many).
* Delete previous organization selection from CustomerModule.
* Field Select must be multi selectable. Elements are full tree, restricted by user permissions.
* Editing relationships between customer and organizations may be by users that has access to customer and organization ID.
  * Example: administrator of common parent organization.
