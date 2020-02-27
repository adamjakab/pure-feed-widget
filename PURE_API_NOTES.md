PURE API NOTES
==============
These are some notes I managed to gather.

To use the API first of all you will need to get an API key from your Pure administrator.

### API URL:
At the time of writing, the following Pure API urls are accessible:
- `https://pure.api.domain/ws/api/513`
- `https://pure.api.domain/ws/api/516`

### API DOCUMENTATION 
The first and most important thing you have is the on-line API docs.

[https://pure.api.domain/ws/api/516/api-docs/index.html#/](https://pure.api.domain/ws/api/516/api-docs/index.html#/)

Here you can authenticate with your API key, see what you have access to and you can try out the different endpoints.

Elsevier Pure uses https://swagger.io/ to build the interactive docs. The position of the schema document is here:
[https://pure.api.domain/ws/api/516/swagger.json](https://pure.api.domain/ws/api/516/swagger.json)


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
