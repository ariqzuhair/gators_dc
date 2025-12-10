# Frontend Setup Guide

## Initial Setup

After starting the Docker containers:

### 1. Install Dependencies
```bash
docker exec -it gators_frontend npm install
```

### 2. Start Development Server
The development server should start automatically via docker-compose. If not:
```bash
docker exec -it gators_frontend npm run dev
```

## Development

### Running Commands
```bash
# Access frontend container
docker exec -it gators_frontend sh

# Run linter
npm run lint

# Build for production
npm run build

# Preview production build
npm run preview
```

### Environment Variables
Edit `.env` file to configure:
```
VITE_API_URL=http://localhost:8000/api
```

## Component Structure

### Layouts
- `MainLayout.vue` - Main application layout with navbar and footer
- `AuthLayout.vue` - Authentication pages layout

### Views
- `HomePage.vue` - Landing page
- `SessionsPage.vue` - List all sessions with filters
- `SessionDetailPage.vue` - Session details and registration
- `PlayersPage.vue` - Player management (admin)
- `ProfilePage.vue` - User profile page
- `AdminDashboard.vue` - Admin dashboard
- `auth/LoginPage.vue` - Login page
- `auth/RegisterPage.vue` - Registration page

### Components
- `NavBar.vue` - Navigation bar
- `Footer.vue` - Footer
- `SessionCard.vue` - Session card component

### Stores (Pinia)
- `auth.js` - Authentication state management
- `session.js` - Session data management
- `registration.js` - Registration management

## Styling

### Tailwind CSS
The project uses Tailwind CSS with custom configurations:

#### Custom Colors
- Primary: Blue shades
- Secondary: Red shades

#### Custom Components
- `.btn` - Button base
- `.btn-primary` - Primary button
- `.btn-secondary` - Secondary button
- `.btn-danger` - Danger button
- `.card` - Card container
- `.input` - Input field
- `.label` - Form label

## API Integration

All API calls go through `src/services/api.js` which:
- Adds authentication token to requests
- Handles 401 errors (redirects to login)
- Provides base URL configuration

## Troubleshooting

### Hot Reload Not Working
```bash
# Restart frontend container
docker-compose restart frontend
```

### Port Already in Use
Change the port in `docker-compose.yml` and `vite.config.js`

### Node Modules Issues
```bash
# Remove node_modules and reinstall
docker exec -it gators_frontend rm -rf node_modules
docker exec -it gators_frontend npm install
```
