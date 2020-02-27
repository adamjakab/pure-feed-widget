PURE API NOTES
==============

These are some notes I managed to gather.

### API URL:
The following Pure API urls are accessible:
- `https://<domain>/ws/api/513`
- `https://<domain>/ws/api/516`

### API DOCUMENTATION 


https://<domain>/ws/api/516/api-docs/index.html#/

schema:
https://<domain>/ws/api/516/swagger.json



### RESEARCH OUTPUT 

Endpoint: `/research-outputs`

 ####Allowed keys:
 * size
 * forJournals
 * keywordUris
 * offset
 * fallbackLocales
 * uuids
 * searchString
 * publishedBeforeDate
 * publishedAfterDate
 * orderings
 * forPublishers
 * idClassification
 * internationalPeerReviewed
 * modifiedBefore
 * publicationStatuses
 * navigationLink
 * authorRoles
 * forPersons
 * freeKeywords
 * ids
 * peerReviewed
 * linkingStrategy
    - allowed values: 
        - documentLinkingStrategy
        - portalLinkingStrategy
        - noLinkingStrategy
        - externalSourceIdLinkingStrategy
 * modifiedAfter,
 * locales,
 * forOrganisationalUnits,
 * renderings,
 * workflowSteps,
 * returnUsedContent,
 * fields,
 * typeUris,
 * publicationCategories
 *

### PERSONS

Endpoint: `/persons`

 ####Allowed keys:
 * size
 * renderings
 * modifiedBefore
 * returnUsedContent
 * employmentPeriod
 * navigationLink
 * forOrganisations
 * offset
 * freeKeywords
 * fallbackLocales
 * ids
 * academicStaff
 * linkingStrategy
 * employmentStatus
 * orderings
 * idClassification
 * personOrganisationAssociationTypes
 * employmentTypeUris
 * keywordUris
 * modifiedAfter
 * searchString
 * fields
 * uuids
 * locales


EXAMPLE:
```json
{
    "size": 25,
    "offset": 0,
    "locales": [
        "en_GB"
    ],
    "orderings": [
        "lastName"
    ],
    "fields": [
        "pureId",
        "uuid",
        "name.firstName",
        "name.lastName",
        "profilePhotos.url",
        "profileInformations.type",
        "profileInformations.value",
        "staffOrganisationAssociations.organisationalUnit.name.value"
    ],
    "forOrganisations": {
        "uuids": [
            "c3c5ad6f-a39f-411e-b880-4c9045cc60a2"
        ]
    }
}
```

### ACKNOWLEDGEMETS:
Great thanks to [David](https://github.com/nihiliad) for providing [super useful information](https://github.com/UMNLibraries/pureapi/issues/6) when I was in the dark...

