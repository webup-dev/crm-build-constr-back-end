# User-Details. Responses
| Method                  | Description                            | Code |
|:------------------------|:---------------------------------------|:----:|
| index                   | Correct index                          | 200  |
|                         | Empty index                            | 204  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| show                    | Correct                                | 200  |
|                         | Permission is absent due to Role       | 453  |
|                         | Permission to the department is absent | 454  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|-------------------------|----------------------------------------|------|
| store                   | Correct                                | 200  |
|                         | The given data was invalid             | 422  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| update                  | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | The given data was invalid             | 422  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| softDelete              | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| index with soft-deleted | Correct index                          | 200  |
|                         | Empty index                            | 204  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| restore                 | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
| permanentDestroy        | Correct                                | 200  |
|                         | Incorrect the Entity ID in the URL     | 456  |
|                         | Permission is absent due to Role       | 453  |
|-------------------------|----------------------------------------|------|
