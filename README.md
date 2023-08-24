# Twilio Sms Rest Api
## Project start date: 24.08.2023 15:13 <br> Project end date: 24.08.2023 21:53
## V1 API (You can find Postman Collection on files)
### # Form
    GET /api/v1/form?{status?=pending|approved|rejected|auto-rejected}
        headers: {
            "x-access-token": ""
        }    
    POST /api/v1/form
        body: {
            name: "Emre",
            surname: "Cal",
            phone_number: "+905551112233",
            email: "emrecaal@gmail.com",
            birthdate: "2023-01-24", // YYYY-MM-DD | Optional
        }
        headers: {
            "x-access-token": ""
        }
    PUT /api/v1/form/{form_id}
        body: {
            status: "pending|approved|rejected|auto-rejected"
        }
        headers: {
            "x-access-token": ""
        }

