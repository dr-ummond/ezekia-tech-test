# Ezekia Tech Test

## Overview

This project demonstrates a simple API that includes key financial handling features, such as storing hourly rates, using exchange rates, and providing a secure user endpoint. The goal is to provide a clean, maintainable, and secure API for financial and user management operations.

## Key Features

- **Hourly Rate:**
    - The hourly rate is stored as an integer and returned as an integer by the API. This aligns with best practices for handling financial data. The consumer of the API is responsible for formatting the hourly rate as necessary.

- **Exchange Rates:**
    - The API implementation uses the latest free Exchange Rates API. If project funding becomes available, the paid `convert` endpoint can be utilized for more advanced functionality.

- **User Endpoints:**
    - The user endpoint uses UUID as route model binding, protecting user IDs for enhanced security. Although this is not a critical concern in this project, it follows best practices and is a habit to adopt in modern API development.

- **Show Endpoint:**
    - The show endpoint does not require the currency query parameter to be passed through the URL. It defaults to the currency set against the user, simplifying the request process.

## API Endpoints

### User Routes

- **GET** `/user/{user}` – Show User  
  Retrieves user details by their UUID. The currency will default to the one set for the user.

- **POST** `/user` – Create User  
  Creates a new user. The body of the request should include user details such as:
    - `first_name`
    - `last_name`
    - `hourly_rate`
    - `currency`
    - `bio`

- **PUT** `/user/{user}` – Update User  
  Updates the information of an existing user by their UUID. The body of the request should include the fields to be updated.

- **DELETE** `/user/{user}` – Delete User  
  Deletes the user with the specified UUID.

## Testing

Test coverage is provided using **Pest**, a modern PHP testing framework that ensures the correctness and reliability of the implemented features.
