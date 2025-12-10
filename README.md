# Gators Dodgeball Club Management System

A full-stack web application for managing a dodgeball club, allowing players to register for sessions and administrators to manage club operations.

## 🏗️ Tech Stack

### Frontend
- **Vue.js 3** - Progressive JavaScript framework
- **Tailwind CSS** - Utility-first CSS framework
- **Pinia** - State management
- **Vue Router** - Routing
- **Axios** - HTTP client
- **Vite** - Build tool

### Backend
- **Laravel 10** - PHP framework
- **MongoDB** - NoSQL database
- **Laravel MongoDB** - MongoDB integration for Laravel

### Infrastructure
- **Docker** - Containerization
- **Docker Compose** - Multi-container orchestration
- **Nginx** - Web server

## 📋 Features

### For Players
- ✅ User registration and authentication
- ✅ Browse available sessions (drop-in, training, tournaments)
- ✅ Register for sessions
- ✅ View and manage registrations
- ✅ Profile management
- ✅ Filter sessions by type, skill level, and date

### For Administrators
- ✅ Create and manage sessions
- ✅ View all players and registrations
- ✅ Monitor session capacity
- ✅ Admin dashboard

## 🚀 Getting Started

### Prerequisites
- Docker Desktop installed
- Git

### Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd gators_dc
```

2. **Set up environment variables**

Backend:
```bash
cd backend
cp .env.example .env
# Update .env with your configuration if needed
```

Frontend:
```bash
cd ../frontend
# .env file is already created
```

3. **Start Docker containers**
```bash
cd ..
docker-compose up -d
```

This will start:
- MongoDB on port `27017`
- Laravel backend on port `8000`
- Vue.js frontend on port `5173`
- Nginx on port `80`

4. **Install Laravel dependencies**
```bash
docker exec -it gators_backend composer install
```

5. **Generate Laravel application key**
```bash
docker exec -it gators_backend php artisan key:generate
```

6. **Install frontend dependencies**
```bash
docker exec -it gators_frontend npm install
```

7. **Access the application**
- Frontend: http://localhost:5173
- Backend API: http://localhost:8000
- Nginx: http://localhost

## 📁 Project Structure

```
gators_dc/
├── backend/                    # Laravel backend
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       └── Api/       # API controllers
│   │   └── Models/            # Eloquent models
│   ├── config/                # Configuration files
│   ├── routes/
│   │   └── api.php           # API routes
│   ├── composer.json
│   ├── Dockerfile
│   └── .env.example
│
├── frontend/                   # Vue.js frontend
│   ├── src/
│   │   ├── assets/           # CSS and static assets
│   │   ├── components/       # Reusable Vue components
│   │   ├── layouts/          # Layout components
│   │   ├── router/           # Vue Router configuration
│   │   ├── services/         # API services
│   │   ├── stores/           # Pinia stores
│   │   ├── views/            # Page components
│   │   ├── App.vue
│   │   └── main.js
│   ├── index.html
│   ├── package.json
│   ├── vite.config.js
│   ├── tailwind.config.js
│   ├── Dockerfile
│   └── .env
│
├── docker/                     # Docker configuration
│   ├── mongodb/
│   │   └── init-mongo.js     # MongoDB initialization script
│   ├── nginx/
│   │   └── default.conf      # Nginx configuration
│   └── php/
│       └── local.ini         # PHP configuration
│
├── docker-compose.yml
├── .env
├── .dockerignore
└── README.md
```

## 🗄️ Database Schema

### Collections

#### users
- name, email, password
- role (admin, player)
- phone, is_active

#### players
- user_id (reference to users)
- membership_type (regular, premium, trial)
- membership_start_date, membership_end_date
- skill_level (beginner, intermediate, advanced)
- emergency_contact_name, emergency_contact_phone
- medical_conditions, profile_image

#### sessions
- title, description
- type (drop-in, training, tournament, special-event)
- date, start_time, end_time
- location
- max_participants, current_participants
- price, skill_level_required
- created_by (admin user_id)

#### registrations
- player_id, session_id
- status (registered, cancelled, completed, no-show)
- payment_status (pending, paid, refunded)
- payment_amount
- registration_date, cancellation_date

## 🔌 API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/me` - Get current user

### Players
- `GET /api/players` - List all players
- `POST /api/players` - Create player profile
- `GET /api/players/{id}` - Get player details
- `PUT /api/players/{id}` - Update player
- `DELETE /api/players/{id}` - Delete player

### Sessions
- `GET /api/sessions` - List all sessions
- `GET /api/sessions/upcoming` - Get upcoming sessions
- `POST /api/sessions` - Create session (admin)
- `GET /api/sessions/{id}` - Get session details
- `PUT /api/sessions/{id}` - Update session (admin)
- `DELETE /api/sessions/{id}` - Delete session (admin)

### Registrations
- `GET /api/registrations` - List registrations
- `POST /api/registrations` - Register for session
- `GET /api/registrations/{id}` - Get registration details
- `PUT /api/registrations/{id}` - Update registration
- `POST /api/registrations/{id}/cancel` - Cancel registration
- `DELETE /api/registrations/{id}` - Delete registration

## 🛠️ Development

### Running Commands

**Backend (Laravel)**
```bash
# Access backend container
docker exec -it gators_backend bash

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear

# Run tests
php artisan test
```

**Frontend (Vue.js)**
```bash
# Access frontend container
docker exec -it gators_frontend sh

# Run linter
npm run lint

# Build for production
npm run build
```

### Stopping the Application
```bash
docker-compose down
```

### Rebuilding Containers
```bash
docker-compose down
docker-compose build
docker-compose up -d
```

## 📝 Next Steps

### Immediate Implementation Needed
1. **Complete Authentication** - Implement JWT or Laravel Sanctum
2. **Complete Remaining Views**:
   - SessionDetailPage.vue
   - PlayersPage.vue
   - ProfilePage.vue
   - AdminDashboard.vue

### Future Features
- [ ] Payment integration (Stripe/PayPal)
- [ ] Email notifications
- [ ] Attendance tracking
- [ ] Player statistics and leaderboards
- [ ] Team formation and management
- [ ] Tournament brackets
- [ ] Mobile responsive improvements
- [ ] Progressive Web App (PWA)
- [ ] Real-time updates with WebSockets
- [ ] Export reports (PDF/Excel)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📄 License

This project is private and proprietary.

## 👥 Contact

For questions or support, contact the development team.

---

**Built with ❤️ for the Gators Dodgeball Club**
