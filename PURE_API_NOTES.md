PURE API NOTES
==============

These are some notes I managed to gather mostly from the error messages.

###API URL:
On the Pure deployment I was working on after some trial and error I found out that the following urls were accessible:
- `https://<domain>/ws/api/513`
- `https://<domain>/ws/api/516`

I ended up using the 513 version (I assume it refers to the version of the API v5.1.3.).
Everything that follows in these notes are through the API calls to this version.


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

