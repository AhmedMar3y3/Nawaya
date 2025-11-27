<?php

/*

- The next feature we are going to work on the workshops feature on the admin dashboard.
- this feature will allow admin to index, show, create, edit, delete and export excel workshops.
- first the index that is going to be a table paginated just like the users table and also would have 2 tabs for the existing and deleted workshops like the users table exactly.
- the index table will have the following columns:
   - title
   - teacher
   - start_date
   - type (localized from the enums file in the lang)
   - number of subscribers
   - is_active (which would be a toggle button to activate/deactivate the workshop)
   - actions (view, edit, delete)
- the create/edit form must be dynamic based on the type of the workshop.
- first the general fields that are common for all types:
   - title
   - teacher
   - teacher percentage
   - description
   - subject_of_discussion
   - type (select field)
- the types are: online, onsite, onsite_online, recorded.
- if the type is online, the form must show the following fields:
    - start_date
    - end_date (optional)
    - start_time
    - end_time (optional)
    - zoom_link
- if the type is onsite, the form must show the following fields:
    - start_date
    - end_date (optional)
    - start_time
    - end_time (optional)
    - city
    - country (select field)
    - hotel
    - hall
- if the type is onsite_online, the form must show the following fields:
    - start_date
    - end_date
    - start_time
    - end_time
    - zoom_link
    - city
    - country (select field)
    - hotel
    - hall

- if the type is recorded, it has no additional fields. in the workshop itself.

- every thing is going to be a modal popup.
- now for the other stuff first the packages each workshop can be associated with multiple packages except for the recorded can only have one package.
- when creating/editing a workshop there must be a section for adding/removing packages dynamically.
- when creating/editing a workshop there must be a section for adding/removing attachments dynamically.
- when creating/editing a workshop there must be a section for adding/removing files dynamically.
- so when creating/editing a workshop we can add the workshop details + packages + attachments + files all in one form dynamically.
- and for the recorded workshops type it has another thing which is the workshop_recordings which has a workshop_id, title, link fields and also dynamic addition/removal.
- and again the recorded workshops can only be associated with one package.
- when the end_date of the online/online_onsite workshop is reached the workshop must be automatically changed the type to be recorded and inactive. 
- the code of the controller must be clean and use a service class to handle the business logic.
- also make a form request to handle the validation logic for creating/editing workshops.
- the form of the edit has the same details as the create form.
- when the workshop is soft deleted all its related data must be soft deleted as well (recordings, attachments, files, packages).
- when restoring a soft deleted workshop all its related data must be restored as well (recordings, attachments, files, packages).
- when permanently deleting a soft deleted workshop all its related data must be permanently deleted as well (recordings, attachments, files, packages).


- for the workshop attachment it has 2 types: image, video also are in the enums if the lang directory, must be handled in the validation.
- any file or image uploaded no need to make logic for them as the package I'm using that is used in the models with the name HasImage is handling that already, the uploading and saving and returning the full url.
*/