CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE events (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE tickets (
  id INT AUTO_INCREMENT,
  event_id INT NOT NULL,
  ticket_number VARCHAR(255) NOT NULL,
  ticket_type ENUM('general', 'vip') NOT NULL DEFAULT 'general',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE attendance (
  id INT AUTO_INCREMENT,
  event_id INT NOT NULL,
  user_id INT NOT NULL,
  attendance_status ENUM('present', 'absent') NOT NULL DEFAULT 'absent',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO events (name, description, start_date, end_date) VALUES
  ('Event 1', 'Event 1 description', '2024-01-01', '2024-01-02'),
  ('Event 2', 'Event 2 description', '2024-01-15', '2024-01-16');

INSERT INTO tickets (event_id, ticket_number, ticket_type) VALUES
  (1, 'Ticket 1', 'general'),
  (1, 'Ticket 2', 'vip'),
  (2, 'Ticket 3', 'general');

INSERT INTO attendance (event_id, user_id, attendance_status) VALUES
  (1, 1, 'present'),
  (2, 1, 'absent');