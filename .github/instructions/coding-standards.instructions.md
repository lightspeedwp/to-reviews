# Coding Standards Instructions

## WordPress Coding Standards for TO Reviews Plugin

### PHP Standards

Follow WordPress PHP Coding Standards (WPCS) strictly:

#### Naming Conventions

- **Classes:** Use `TO_Reviews_` prefix with Pascal_Case
- **Functions:** Use `to_reviews_` prefix with snake_case
- **Variables:** Use snake_case (`$review_data`, `$user_rating`)
- **Constants:** Use UPPER_SNAKE_CASE (`TO_REVIEWS_VERSION`)

#### Code Structure

```php
<?php
/**
 * Class description
 *
 * @package TO_Reviews
 * @since 1.0.0
 */
class TO_Reviews_Display {

    /**
     * Method description
     *
     * @param int $review_id Review ID.
     * @return string Review HTML output.
     */
    public function display_review( $review_id ) {
        // Implementation
    }
}
```

#### Security Best Practices

1. **Sanitization:** Always sanitize input data

   ```php
   $review_title = sanitize_text_field( $_POST['review_title'] );
   ```

2. **Validation:** Validate all data before processing

   ```php
   if ( ! is_numeric( $rating ) || $rating < 1 || $rating > 5 ) {
       return false;
   }
   ```

3. **Escaping:** Escape all output

   ```php
   echo esc_html( $review_title );
   echo wp_kses_post( $review_content );
   ```

4. **Nonces:** Use nonces for form submissions
   ```php
   wp_nonce_field( 'to_reviews_submit', 'to_reviews_nonce' );
   ```

#### Database Operations

- Use `$wpdb->prepare()` for all queries
- Prefix all custom tables with `$wpdb->prefix`
- Use WordPress database functions when possible

#### Hooks and Filters

- Prefix all hooks with `to_reviews_`
- Document all hooks with proper DocBlocks
- Provide examples in hook documentation

### File Organization

- One class per file
- Use autoloading for classes
- Keep functions in separate includes
- Organize by functionality, not file type

### Documentation

- All functions must have DocBlocks
- Use @since tags for version tracking
- Include @param and @return documentation
- Add inline comments for complex logic

### Performance

- Cache expensive operations
- Use transients for temporary data
- Minimize database queries
- Optimize for mobile and slow connections
