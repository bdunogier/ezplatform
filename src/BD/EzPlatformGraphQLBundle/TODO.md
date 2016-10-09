- Implement missing FieldValue objects
- Fix FieldValue objects: it should not be necessary to redefine every resolver for every field.
  The Value should be directly usable. Maybe the `ContentFieldValue` object should be changed 
  to a Proxy of FieldValue ?
- Think about search support
- Suggest the repo team to implement cursors for search
- Think about Global ID / Node support
- Think about Content interface / dedicated Content types.
  Dedicated ContentType objects would be instances of their ContentType, and provide
  direct access to their fields.
  `ArticleContentType` would have `title`, `intro`, `body` and `image` fields.
    - FolderContent
    

