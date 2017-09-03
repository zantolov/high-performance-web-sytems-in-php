CREATE INDEX post_category ON posts (category_id);
CREATE INDEX post_created_at ON posts (created_at);

CREATE INDEX category_title ON categories (title);