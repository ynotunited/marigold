<?php

return [
    'google_id_column' => "ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL AFTER avatar",
    'idx_users_google_id' => "CREATE INDEX idx_users_google_id ON users (google_id)",
];
