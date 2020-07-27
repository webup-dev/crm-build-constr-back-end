# Customer-Files. Back End. Responses
| Method                  | Description                            | Code |
|:------------------------|:---------------------------------------|:----:|
| index                   | Correct index                          | 200  |
|                         | Empty index                            | 204  |
|                         | Permission is absent due to Role       | 453  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|-------------------------|----------------------------------------|------|
| show                    | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| store                   | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | The given data was invalid             | 422  |
|                         | File extension is invalid              | 422  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| update                  | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | The given data was invalid             | 422  |
|                         | File extension is invalid              | 422  |
|                         | Permission is absent due to Role       | 453  |
|                         | You are not the author                 | 457  |
|-------------------------|----------------------------------------|------|
| softDelete              | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | Permission is absent due to Role       | 453  |
|                         | You are not the author                 | 457  |
|-------------------------|----------------------------------------|------|
| index with soft-deleted | Correct index                          | 200  |
|                         | Empty index                            | 204  |
|                         | Permission is absent due to Role       | 453  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|-------------------------|----------------------------------------|------|
| restore                 | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| permanentDestroy        | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
