# BuildBook API Documentation

## Overview
This document describes the REST API endpoints for the BuildBook application, a sewing project management system built with Laravel and Laravel Sanctum for authentication.

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

---

## Important Notes
- Fields such as `share`, `due_date`, `completion_date`, `amount`, `est_cost`, `actual_cost`, and `source` may be omitted from responses if not set (i.e., if their value is `null`).
- The `notes` array is always present in project, task, and material responses (may be empty).
- When fetching a project, task, or material, the response includes associated models (e.g., tasks, materials, notes for a project).

---

## Authentication Endpoints

### Register User
**POST** `/register`

Creates a new user account.

**Request Body:**
```json
{
    "username": "string|required|unique:users",
    "email": "string|required|email|unique:users",
    "password": "string|required|min:8|confirmed",
    "password_confirmation": "string|required",
    "handle": "string|nullable|max:255",
    "bio": "string|nullable"
}
```

**Response (201):**
```json
{
    "user": {
        "id": 1,
        "username": "johndoe",
        "email": "john@example.com",
        "handle": "john_doe",
        "bio": "Sewing enthusiast",
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z"
    },
    "token": "1|abc123..."
}
```

### Login User
**POST** `/login`

Authenticates a user and returns an access token.

**Request Body:**
```json
{
    "email": "string|required|email",
    "password": "string|required"
}
```

**Response (200):**
```json
{
    "user": {
        "id": 1,
        "username": "johndoe",
        "email": "john@example.com",
        "handle": "john_doe",
        "bio": "Sewing enthusiast",
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z"
    },
    "token": "1|abc123..."
}
```

### Logout User
**POST** `/logout`

Revokes the current access token.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "message": "Logged out successfully"
}
```

---

## User Profile Endpoints

### Get User Profile
**GET** `/profile`

Returns the authenticated user's profile information.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "handle": "john_doe",
    "bio": "Sewing enthusiast",
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z"
}
```

### Update User Profile
**PUT** `/profile`

Updates the authenticated user's profile information.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "username": "string|required|unique:users,username,{user_id}",
    "email": "string|required|email|unique:users,email,{user_id}",
    "handle": "string|nullable|max:255",
    "bio": "string|nullable"
}
```

**Response (200):**
```json
{
    "id": 1,
    "username": "johndoe_updated",
    "email": "john_updated@example.com",
    "handle": "john_doe_updated",
    "bio": "Updated bio",
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z"
}
```

### Delete User Profile
**DELETE** `/profile`

Deletes the authenticated user's account.

**Headers:** `Authorization: Bearer {token}`

**Response (204):** No content

---

## Project Endpoints

### List Projects
**GET** `/projects`

Returns all projects belonging to the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
[
    {
        "id": 1,
        "user_id": 1,
        "name": "Summer Dress",
        "series": "Summer Collection",
        "version": "1.0",
        "description": "A beautiful summer dress",
        "share": true,
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z",
        "user": {
            "id": 1,
            "username": "johndoe",
            "email": "john@example.com"
        },
        "tasks": [
            { "id": 1, "title": "Cut Fabric", ... },
            { "id": 2, "title": "Sew Seams", ... }
        ],
        "materials": [
            { "id": 1, "name": "Cotton Fabric", ... }
        ],
        "notes": [
            {
                "id": 1,
                "content": "Remember to pre-wash fabric",
                "created_at": "2025-07-04T20:00:00.000000Z",
                "updated_at": "2025-07-04T20:00:00.000000Z"
            }
        ]
    }
]
```

### Create Project
**POST** `/projects`

Creates a new project for the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "name": "string|required|max:255",
    "series": "string|nullable|max:255",
    "version": "string|nullable|max:255",
    "description": "string|nullable",
    "share": "boolean|default:false"
}
```

**Response (201):**
```json
{
    "id": 1,
    "user_id": 1,
    "name": "Summer Dress",
    "series": "Summer Collection",
    "version": "1.0",
    "description": "A beautiful summer dress",
    "share": true,
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z",
    "user": {
        "id": 1,
        "username": "johndoe",
        "email": "john@example.com"
    }
}
```

### Get Project
**GET** `/projects/{id}`

Returns a specific project belonging to the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "id": 1,
    "user_id": 1,
    "name": "Summer Dress",
    "series": "Summer Collection",
    "version": "1.0",
    "description": "A beautiful summer dress",
    "share": true,
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z",
    "user": {
        "id": 1,
        "username": "johndoe",
        "email": "john@example.com"
    }
}
```

**Response (404):** Project not found or doesn't belong to user

### Update Project
**PUT** `/projects/{id}`

Updates a specific project belonging to the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "name": "string|required|max:255",
    "series": "string|nullable|max:255",
    "version": "string|nullable|max:255",
    "description": "string|nullable",
    "share": "boolean"
}
```

**Response (200):**
```json
{
    "id": 1,
    "user_id": 1,
    "name": "Updated Summer Dress",
    "series": "Updated Summer Collection",
    "version": "2.0",
    "description": "An updated beautiful summer dress",
    "share": false,
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z",
    "user": {
        "id": 1,
        "username": "johndoe",
        "email": "john@example.com"
    }
}
```

**Response (404):** Project not found or doesn't belong to user

### Delete Project
**DELETE** `/projects/{id}`

Deletes a specific project belonging to the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (204):** No content

**Response (404):** Project not found or doesn't belong to user

---

## Task Endpoints

### List Tasks
**GET** `/tasks`

Returns all tasks belonging to projects owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
[
    {
        "id": 1,
        "project_id": 1,
        "title": "Cut Fabric",
        "description": "Cut the main fabric pieces",
        "share": true,
        "due_date": "2025-07-10T00:00:00.000000Z",
        "completion_date": null,
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z",
        "project": {
            "id": 1,
            "name": "Summer Dress",
            "user_id": 1
        },
        "notes": [
            {
                "id": 1,
                "content": "Remember to pre-wash fabric",
                "created_at": "2025-07-04T20:00:00.000000Z",
                "updated_at": "2025-07-04T20:00:00.000000Z"
            }
        ]
    }
]
```

### Create Task
**POST** `/tasks`

Creates a new task for a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "project_id": "integer|required|exists:projects,id",
    "title": "string|required|max:255",
    "description": "string|nullable",
    "share": "boolean|default:false",
    "due_date": "datetime|nullable",
    "completion_date": "datetime|nullable"
}
```

**Response (201):**
```json
{
    "id": 1,
    "project_id": 1,
    "title": "Cut Fabric",
    "description": "Cut the main fabric pieces",
    "share": true,
    "due_date": "2025-07-10T00:00:00.000000Z",
    "completion_date": null,
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z",
    "project": {
        "id": 1,
        "name": "Summer Dress",
        "user_id": 1
    }
}
```

### Get Task
**GET** `/tasks/{id}`

Returns a specific task belonging to a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "id": 1,
    "project_id": 1,
    "title": "Cut Fabric",
    "description": "Cut the main fabric pieces",
    "share": true,
    "due_date": "2025-07-10T00:00:00.000000Z",
    "completion_date": null,
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z",
    "project": {
        "id": 1,
        "name": "Summer Dress",
        "user_id": 1
    }
}
```

**Response (404):** Task not found or doesn't belong to user's project

### Update Task
**PUT** `/tasks/{id}`

Updates a specific task belonging to a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "title": "string|required|max:255",
    "description": "string|nullable",
    "share": "boolean",
    "due_date": "datetime|nullable",
    "completion_date": "datetime|nullable"
}
```

**Response (200):**
```json
{
    "id": 1,
    "project_id": 1,
    "title": "Updated Cut Fabric",
    "description": "Updated cutting instructions",
    "share": false,
    "due_date": "2025-07-15T00:00:00.000000Z",
    "completion_date": "2025-07-05T00:00:00.000000Z",
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z",
    "project": {
        "id": 1,
        "name": "Summer Dress",
        "user_id": 1
    }
}
```

**Response (404):** Task not found or doesn't belong to user's project

### Delete Task
**DELETE** `/tasks/{id}`

Deletes a specific task belonging to a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (204):** No content

**Response (404):** Task not found or doesn't belong to user's project

---

## Material Endpoints

### List Materials
**GET** `/projects/{project}/materials`

Returns all materials for a given project.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
[
    {
        "id": 1,
        "project_id": 1,
        "name": "Cotton Fabric",
        "description": "Main fabric for dress",
        "amount": "2 yards",
        "est_cost": 1200,
        "actual_cost": 1300,
        "source": "Local Fabric Store",
        "acquired": true,
        "share": false,
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z",
        "notes": [
            {
                "id": 1,
                "content": "Buy extra for mistakes",
                "created_at": "2025-07-04T20:00:00.000000Z",
                "updated_at": "2025-07-04T20:00:00.000000Z"
            }
        ]
    }
]
```

### Create Material
**POST** `/projects/{project}/materials`

Creates a new material for a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "name": "string|required|max:255",
    "description": "string|nullable",
    "amount": "string|nullable",
    "est_cost": "integer|nullable",
    "actual_cost": "integer|nullable",
    "source": "string|nullable|max:255",
    "acquired": "boolean|default:false",
    "share": "boolean|default:false"
}
```

**Response (201):**
```json
{
    "message": "Material created successfully",
    "data": {
        "id": 1,
        "project_id": 1,
        "name": "Cotton Fabric",
        "description": "Main fabric for dress",
        "amount": "2 yards",
        "est_cost": 1200,
        "actual_cost": null,
        "source": "Local Fabric Store",
        "acquired": false,
        "share": false,
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z",
        "notes": []
    }
}
```

### Get Material
**GET** `/materials/{material}`

Returns a specific material belonging to a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "data": {
        "id": 1,
        "project_id": 1,
        "name": "Cotton Fabric",
        "description": "Main fabric for dress",
        "amount": "2 yards",
        "est_cost": 1200,
        "actual_cost": 1300,
        "source": "Local Fabric Store",
        "acquired": true,
        "share": false,
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z",
        "notes": [
            {
                "id": 1,
                "content": "Buy extra for mistakes",
                "created_at": "2025-07-04T20:00:00.000000Z",
                "updated_at": "2025-07-04T20:00:00.000000Z"
            }
        ]
    }
}
```

### Update Material
**PUT** `/materials/{material}`

Updates a specific material belonging to a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "name": "string|required|max:255",
    "description": "string|nullable",
    "amount": "string|nullable",
    "est_cost": "integer|nullable",
    "actual_cost": "integer|nullable",
    "source": "string|nullable|max:255",
    "acquired": "boolean",
    "share": "boolean"
}
```

**Response (200):**
```json
{
    "message": "Material updated successfully",
    "data": {
        "id": 1,
        "project_id": 1,
        "name": "Updated Cotton Fabric",
        "description": "Updated description",
        "amount": "3 yards",
        "est_cost": 1500,
        "actual_cost": 1400,
        "source": "Online Store",
        "acquired": true,
        "share": true,
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:05:00.000000Z",
        "notes": []
    }
}
```

### Delete Material
**DELETE** `/materials/{material}`

Deletes a specific material belonging to a project owned by the authenticated user.

**Headers:** `Authorization: Bearer {token}`

**Response (204):** No content

---

## Notes Endpoints

Notes are polymorphic and can be attached to projects, tasks, or materials. The endpoints follow the pattern:
- `/projects/{project}/notes`
- `/tasks/{task}/notes`
- `/materials/{material}/notes`

### List Notes
**GET** `/projects/{project}/notes` (or `/tasks/{task}/notes`, `/materials/{material}/notes`)

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
[
    {
        "id": 1,
        "content": "Remember to pre-wash fabric",
        "created_at": "2025-07-04T20:00:00.000000Z",
        "updated_at": "2025-07-04T20:00:00.000000Z"
    }
]
```

### Create Note
**POST** `/projects/{project}/notes` (or `/tasks/{task}/notes`, `/materials/{material}/notes`)

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "content": "string|required"
}
```

**Response (201):**
```json
{
    "id": 2,
    "content": "Attach a swatch",
    "created_at": "2025-07-04T20:05:00.000000Z",
    "updated_at": "2025-07-04T20:05:00.000000Z"
}
```

### Get Note
**GET** `/projects/{project}/notes/{note}` (or `/tasks/{task}/notes/{note}`, `/materials/{material}/notes/{note}`)

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "id": 1,
    "content": "Remember to pre-wash fabric",
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:00:00.000000Z"
}
```

### Update Note
**PUT** `/projects/{project}/notes/{note}` (or `/tasks/{task}/notes/{note}`, `/materials/{material}/notes/{note}`)

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "content": "string|required"
}
```

**Response (200):**
```json
{
    "id": 1,
    "content": "Updated note content",
    "created_at": "2025-07-04T20:00:00.000000Z",
    "updated_at": "2025-07-04T20:10:00.000000Z"
}
```

### Delete Note
**DELETE** `/projects/{project}/notes/{note}` (or `/tasks/{task}/notes/{note}`, `/materials/{material}/notes/{note}`)

**Headers:** `Authorization: Bearer {token}`

**Response (204):** No content

---

## Error Responses

### Validation Errors (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "The field name field is required."
        ]
    }
}
```

### Authentication Error (401)
```json
{
    "message": "Unauthenticated."
}
```

### Not Found Error (404)
```json
{
    "message": "Not Found."
}
```

---

## Data Models

### User
- `id` (integer) - Primary key
- `username` (string) - Unique username
- `email` (string) - Unique email address
- `password` (string) - Hashed password
- `handle` (string, nullable) - User handle
- `bio` (text, nullable) - User biography
- `created_at` (datetime)
- `updated_at` (datetime)

### Project
- `id` (integer) - Primary key
- `user_id` (integer) - Foreign key to users table
- `name` (string) - Project name
- `series` (string, nullable) - Project series
- `version` (string, nullable) - Project version
- `description` (text, nullable) - Project description
- `share` (boolean) - Whether project is shareable
- `created_at` (datetime)
- `updated_at` (datetime)

### Task
- `id` (integer) - Primary key
- `project_id` (integer) - Foreign key to projects table
- `title` (string) - Task title
- `description` (text, nullable) - Task description
- `share` (boolean) - Whether task is shareable
- `due_date` (datetime, nullable) - Task due date
- `completion_date` (datetime, nullable) - Task completion date
- `created_at` (datetime)
- `updated_at` (datetime)

### Material
- `id` (integer) - Primary key
- `project_id` (integer) - Foreign key to projects table
- `name` (string) - Material name
- `description` (text, nullable) - Material description
- `amount` (string, nullable) - Quantity/amount of material
- `est_cost` (integer, nullable) - Estimated cost in cents
- `actual_cost` (integer, nullable) - Actual cost in cents
- `source` (string, nullable) - Where material was purchased
- `acquired` (boolean) - Whether material has been acquired
- `share` (boolean) - Whether material is shareable
- `created_at` (datetime)
- `updated_at` (datetime)

### Note
- `id` (integer) - Primary key
- `content` (text) - Note content
- `noteable_type` (string) - Polymorphic relationship type (Project, Task, or Material)
- `noteable_id` (integer) - Polymorphic relationship ID
- `created_at` (datetime)
- `updated_at` (datetime)

---

## Testing

All endpoints are thoroughly tested using Pest PHP. Test files are located in:
- `tests/Feature/Auth/` - Authentication tests
- `tests/Feature/Project/` - Project API tests
- `tests/Feature/Task/` - Task API tests

Run tests with:
```bash
php artisan test
```

---

## Notes

- All timestamps are in ISO 8601 format
- All protected endpoints require valid Sanctum authentication
- Users can only access their own projects and tasks
- Cascade deletes are implemented (deleting a project deletes all associated tasks)
- Form requests handle all validation logic
- No validation logic exists in controllers (following project requirements) 