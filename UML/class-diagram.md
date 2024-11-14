
```mermaid
classDiagram
    class User {
        -int id
        -string name
        -string email
        -string password
        -string phone
        -boolean isAdmin
        -DateTime createdAt
        -DateTime updatedAt
        +register()
        +login()
        +makeReservation()
        +viewReservations()
    }

    class Reservation {
        -int id
        -int userId
        -string pitchType
        -Date reservationDate
        -Time startTime
        -Time endTime
        -int normalHours
        -int nightHours
        -int totalPrice
        -string status
        -DateTime createdAt
        -DateTime updatedAt
        +create()
        +cancel()
        +calculatePrice()
        +sendConfirmationEmail()
    }

    class Price {
        -int id
        -string pitchType
        -string timeType
        -int price
        -DateTime createdAt
        -DateTime updatedAt
        +getPriceForType()
        +calculateTotalPrice()
    }

    User "1" -- "*" Reservation : makes
    Reservation "*" -- "1" Price : refers to