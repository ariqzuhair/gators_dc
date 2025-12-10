db = db.getSiblingDB('gators_dc');

// Create collections
db.createCollection('users');
db.createCollection('players');
db.createCollection('sessions');
db.createCollection('registrations');

// Create indexes
db.users.createIndex({ "email": 1 }, { unique: true });
db.players.createIndex({ "user_id": 1 });
db.sessions.createIndex({ "date": 1 });
db.sessions.createIndex({ "type": 1 });
db.registrations.createIndex({ "player_id": 1, "session_id": 1 }, { unique: true });

print('Database initialized successfully');
