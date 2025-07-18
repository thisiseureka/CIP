<?php

if ( ! class_exists( 'ACF_Field_User' ) ) :

	/**
	 * ACF_Field_User Class
	 */
	class ACF_Field_User extends acf_field {


		/**
		 * Initializes the field type.
		 *
		 * @date    5/03/2014
		 * @since   ACF 5.0.0
		 */
		function initialize() {
			$this->name          = 'user';
			$this->label         = __( 'User', 'secure-custom-fields' );
			$this->category      = 'relational';
			$this->description   = __( 'Allows the selection of one or more users which can be used to create relationships between data objects.', 'secure-custom-fields' );
			$this->preview_image = acf_get_url() . '/assets/images/field-type-previews/field-preview-user.png';
			$this->doc_url       = 'https://developer.wordpress.org/secure-custom-fields/features/fields/user/';
			$this->tutorial_url  = 'https://developer.wordpress.org/secure-custom-fields/features/fields/user/user-tutorial/';
			$this->defaults      = array(
				'role'                 => '',
				'multiple'             => 0,
				'allow_null'           => 0,
				'return_format'        => 'array',
				'bidirectional_target' => array(),
			);

			// Register filter variations.
			acf_add_filter_variations( 'acf/fields/user/query', array( 'name', 'key' ), 1 );
			acf_add_filter_variations( 'acf/fields/user/result', array( 'name', 'key' ), 2 );
			acf_add_filter_variations( 'acf/fields/user/search_columns', array( 'name', 'key' ), 3 );
			add_filter( 'acf/conditional_logic/choices', array( $this, 'render_field_user_conditional_choices' ), 10, 3 );

			// Add AJAX query.
			add_action( 'wp_ajax_acf/fields/user/query', array( $this, 'ajax_query' ) );
			add_action( 'wp_ajax_nopriv_acf/fields/user/query', array( $this, 'ajax_query' ) );
		}

		/**
		 * Filters choices in user conditions.
		 *
		 * @since ACF 6.3
		 *
		 * @param array  $choices           The selected choice.
		 * @param array  $conditional_field The conditional field settings object.
		 * @param string $rule_value        The rule value.
		 * @return array
		 */
		public function render_field_user_conditional_choices( $choices, $conditional_field, $rule_value ) {
			if ( ! is_array( $conditional_field ) || $conditional_field['type'] !== 'user' ) {
				return $choices;
			}
			if ( ! empty( $rule_value ) ) {
				$user = acf_get_users(
					array(
						'include' => array( $rule_value ),
					)
				);

				$user_result = acf_get_user_result( $user[0] );
				$choices     = array( $user_result['id'] => $user_result['text'] );
			}

			return $choices;
		}

		/**
		 * Renders the field settings HTML.
		 *
		 * @date    23/01/13
		 * @since   ACF 3.6.0
		 *
		 * @param   array $field The ACF field.
		 * @return  void
		 */
		function render_field_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by Role', 'secure-custom-fields' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'role',
					'choices'      => acf_get_user_role_labels(),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All user roles', 'secure-custom-fields' ),
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Return Format', 'secure-custom-fields' ),
					'instructions' => '',
					'type'         => 'radio',
					'name'         => 'return_format',
					'choices'      => array(
						'array'  => __( 'User Array', 'secure-custom-fields' ),
						'object' => __( 'User Object', 'secure-custom-fields' ),
						'id'     => __( 'User ID', 'secure-custom-fields' ),
					),
					'layout'       => 'horizontal',
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Select Multiple', 'secure-custom-fields' ),
					'instructions' => 'Allow content editors to select multiple values',
					'name'         => 'multiple',
					'type'         => 'true_false',
					'ui'           => 1,
				)
			);
		}

		/**
		 * Renders the field settings used in the "Validation" tab.
		 *
		 * @since ACF 6.0
		 *
		 * @param array $field The field settings array.
		 * @return void
		 */
		function render_field_validation_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Allow Null', 'secure-custom-fields' ),
					'instructions' => '',
					'name'         => 'allow_null',
					'type'         => 'true_false',
					'ui'           => 1,
				)
			);
		}

		/**
		 * Renders the field settings used in the "Advanced" tab.
		 *
		 * @since ACF 6.2
		 *
		 * @param array $field The field settings array.
		 * @return void
		 */
		public function render_field_advanced_settings( $field ) {
			acf_render_bidirectional_field_settings( $field );
		}

		/**
		 * Renders the field input HTML.
		 *
		 * @since   ACF 3.6.0
		 *
		 * @param   array $field The ACF field.
		 * @return  void
		 */
		public function render_field( $field ) {
			// Change Field into a select.
			$field['type']    = 'select';
			$field['ui']      = 1;
			$field['ajax']    = 1;
			$field['choices'] = array();
			$field['nonce']   = wp_create_nonce( $field['key'] );

			// Populate choices.
			if ( $field['value'] ) {

				// Clean value into an array of IDs.
				$user_ids = array_map( 'intval', acf_array( $field['value'] ) );

				// Find users in database (ensures all results are real).
				$users = acf_get_users(
					array(
						'include' => $user_ids,
					)
				);

				// Append.
				if ( $users ) {
					foreach ( $users as $user ) {
						$field['choices'][ $user->ID ] = $this->get_result( $user, $field );
					}
				}
			}

			// Render.
			acf_render_field( $field );
		}

		/**
		 * Returns the result text for a given WP_User object.
		 *
		 * @date    1/11/2013
		 * @since   ACF 5.0.0
		 *
		 * @param   WP_User      $user    The WP_User object.
		 * @param   array        $field   The ACF field related to this query.
		 * @param   (int|string) $post_id The post_id being edited.
		 * @return  string
		 */
		function get_result( $user, $field, $post_id = 0 ) {

			// Get user result item.
			$item = acf_get_user_result( $user );

			// Default $post_id to current post being edited.
			$post_id = $post_id ? $post_id : acf_get_form_data( 'post_id' );

			/**
			 * Filters the result text.
			 *
			 * @date    21/5/19
			 * @since   ACF 5.8.1
			 *
			 * @param   string  $item['text'] The user text.
			 * @param   WP_User $user The user object.
			 * @param   array $field The ACF field related to this query.
			 * @param   (int|string) $post_id The post_id being edited.
			 */
			return apply_filters( 'acf/fields/user/result', $item['text'], $user, $field, $post_id );
		}

		/**
		 * Filters the field value after it is loaded from the database.
		 *
		 * @date    23/01/13
		 * @since   ACF 3.6.0
		 *
		 * @param   mixed $value   The field value.
		 * @param   mixed $post_id The post ID where the value is saved.
		 * @param   array $field   The field array containing all settings.
		 * @return  mixed
		 */
		function load_value( $value, $post_id, $field ) {

			// Add compatibility for version 4.
			if ( $value === 'null' ) {
				return false;
			}
			return $value;
		}

		/**
		 * Filters the field value after it is loaded from the database but before it is returned to the front-end API.
		 *
		 * @date    23/01/13
		 * @since   ACF 3.6.0
		 *
		 * @param   mixed $value   The field value.
		 * @param   mixed $post_id The post ID where the value is saved.
		 * @param   array $field   The field array containing all settings.
		 * @return  mixed
		 */
		function format_value( $value, $post_id, $field ) {

			// Bail early if no value.
			if ( ! $value ) {
				return false;
			}

			// Clean value into an array of IDs.
			$user_ids = array_map( 'intval', acf_array( $value ) );

			// Find users in database (ensures all results are real).
			$users = acf_get_users(
				array(
					'include' => $user_ids,
				)
			);

			// Bail early if no users found.
			if ( ! $users ) {
				return false;
			}

			// Format values using field settings.
			$value = array();
			foreach ( $users as $user ) {

				// Return object.
				if ( $field['return_format'] == 'object' ) {
					$item = $user;

					// Return array.
				} elseif ( $field['return_format'] == 'array' ) {
					$item = array(
						'ID'               => $user->ID,
						'user_firstname'   => $user->user_firstname,
						'user_lastname'    => $user->user_lastname,
						'nickname'         => $user->nickname,
						'user_nicename'    => $user->user_nicename,
						'display_name'     => $user->display_name,
						'user_email'       => $user->user_email,
						'user_url'         => $user->user_url,
						'user_registered'  => $user->user_registered,
						'user_description' => $user->user_description,
						'user_avatar'      => get_avatar( $user->ID ),
					);

					// Return ID.
				} else {
					$item = $user->ID;
				}

				// Append item
				$value[] = $item;
			}

			// Convert to single.
			if ( ! $field['multiple'] ) {
				$value = array_shift( $value );
			}

			// Return.
			return $value;
		}

		/**
		 * Filters the field value before it is saved into the database.
		 *
		 * @since   ACF 3.6.0
		 *
		 * @param  mixed $value   The field value.
		 * @param  mixed $post_id The post ID where the value is saved.
		 * @param  array $field   The field array containing all settings.
		 * @return mixed $value   The modified value.
		 */
		public function update_value( $value, $post_id, $field ) {

			// Bail early if no value.
			if ( empty( $value ) ) {
				acf_update_bidirectional_values( array(), $post_id, $field, 'user' );
				return $value;
			}

			// Format array of values.
			// - ensure each value is an id.
			// - Parse each id as string for SQL LIKE queries.
			if ( acf_is_sequential_array( $value ) ) {
				$value = array_map( 'acf_idval', $value );
				$value = array_map( 'strval', $value );

				// Parse single value for id.
			} else {
				$value = acf_idval( $value );
			}

			acf_update_bidirectional_values( acf_get_array( $value ), $post_id, $field, 'user' );

			// Return value.
			return $value;
		}

		/**
		 * Callback for the AJAX query request.
		 *
		 * @date    24/10/13
		 * @since   ACF 5.0.0
		 *
		 * @return  void
		 */
		function ajax_query() {

			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			// Modify Request args.
			if ( isset( $_REQUEST['s'] ) ) {
				$_REQUEST['search'] = sanitize_text_field( $_REQUEST['s'] );
			}
			if ( isset( $_REQUEST['paged'] ) ) {
				$_REQUEST['page'] = absint( $_REQUEST['paged'] );
			}
			// phpcs:enable WordPress.Security.NonceVerification.Recommended

			// Add query hooks.
			add_action( 'acf/ajax/query_users/init', array( $this, 'ajax_query_init' ), 10, 2 );
			add_filter( 'acf/ajax/query_users/args', array( $this, 'ajax_query_args' ), 10, 3 );
			add_filter( 'acf/ajax/query_users/result', array( $this, 'ajax_query_result' ), 10, 3 );
			add_filter( 'acf/ajax/query_users/search_columns', array( $this, 'ajax_query_search_columns' ), 10, 4 );
			// Simulate AJAX request.
			acf_get_instance( 'ACF_Ajax_Query_Users' )->request();
		}

		/**
		 * Runs during the AJAX query initialization.
		 *
		 * @date    9/3/20
		 * @since   ACF 5.8.8
		 *
		 * @param   array          $request The query request.
		 * @param   ACF_Ajax_Query $query   The query object.
		 * @return  void
		 */
		function ajax_query_init( $request, $query ) {
			// Require field and make sure it's a user field.
			if ( ! $query->field || $query->field['type'] !== $this->name ) {
				$query->send( new WP_Error( 'acf_missing_field', __( 'Error loading field.', 'secure-custom-fields' ), array( 'status' => 404 ) ) );
			}

			// Verify that this is a legitimate request using a separate nonce from the main AJAX nonce.
			$nonce = acf_request_arg( 'nonce', '' );
			$key   = acf_request_arg( 'field_key', '' );

			if ( ! acf_verify_ajax( $nonce, $key ) ) {
				$query->send( new WP_Error( 'acf_invalid_request', __( 'Invalid request.', 'secure-custom-fields' ), array( 'status' => 404 ) ) );
			}
		}

		/**
		 * Filters the AJAX query args.
		 *
		 * @date    9/3/20
		 * @since   ACF 5.8.8
		 *
		 * @param   array          $args    The query args.
		 * @param   array          $request The query request.
		 * @param   ACF_Ajax_Query $query   The query object.
		 * @return  array
		 */
		function ajax_query_args( $args, $request, $query ) {

			// Add specific roles.
			if ( $query->field['role'] ) {
				$args['role__in'] = acf_array( $query->field['role'] );
			}

			/**
			 * Filters the query args.
			 *
			 * @date    21/5/19
			 * @since   ACF 5.8.1
			 *
			 * @param   array $args The query args.
			 * @param   array $field The ACF field related to this query.
			 * @param   (int|string) $post_id The post_id being edited.
			 */
			return apply_filters( 'acf/fields/user/query', $args, $query->field, $query->post_id );
		}

		/**
		 * Filters the WP_User_Query search columns.
		 *
		 * @date    9/3/20
		 * @since   ACF 5.8.8
		 *
		 * @param   array         $columns       An array of column names to be searched.
		 * @param   string        $search        The search term.
		 * @param   WP_User_Query $WP_User_Query The WP_User_Query instance.
		 * @return  array
		 */
		function ajax_query_search_columns( $columns, $search, $WP_User_Query, $query ) {

			/**
			 * Filters the column names to be searched.
			 *
			 * @date    21/5/19
			 * @since   ACF 5.8.1
			 *
			 * @param   array $columns An array of column names to be searched.
			 * @param   string $search The search term.
			 * @param   WP_User_Query $WP_User_Query The WP_User_Query instance.
			 * @param   array $field The ACF field related to this query.
			 */
			return apply_filters( 'acf/fields/user/search_columns', $columns, $search, $WP_User_Query, $query->field );
		}

		/**
		 * Filters the AJAX Query result.
		 *
		 * @date    9/3/20
		 * @since   ACF 5.8.8
		 *
		 * @param   array          $item  The choice id and text.
		 * @param   WP_User        $user  The user object.
		 * @param   ACF_Ajax_Query $query The query object.
		 * @return  array
		 */
		function ajax_query_result( $item, $user, $query ) {

			/**
			 * Filters the result text.
			 *
			 * @date    21/5/19
			 * @since   ACF 5.8.1
			 *
			 * @param   string $item['text'] The result text.
			 * @param   WP_User $user The user object.
			 * @param   array $field The ACF field related to this query.
			 * @param   (int|string) $post_id The post_id being edited.
			 */
			$item['text'] = apply_filters( 'acf/fields/user/result', $item['text'], $user, $query->field, $query->post_id );
			return $item;
		}

		/**
		 * Return an array of data formatted for use in a select2 AJAX response.
		 *
		 * @date    15/10/2014
		 * @since   ACF 5.0.9
		 * @deprecated 5.8.9
		 *
		 * @param   array $args An array of query args.
		 * @return  array
		 */
		function get_ajax_query( $options = array() ) {
			_deprecated_function( __FUNCTION__, '5.8.9' );
			return array();
		}

		/**
		 * Filters the WP_User_Query search columns.
		 *
		 * @date    15/10/2014
		 * @since   ACF 5.0.9
		 * @deprecated 5.8.9
		 *
		 * @param   array         $columns       An array of column names to be searched.
		 * @param   string        $search        The search term.
		 * @param   WP_User_Query $WP_User_Query The WP_User_Query instance.
		 * @return  array
		 */
		function user_search_columns( $columns, $search, $WP_User_Query ) {
			_deprecated_function( __FUNCTION__, '5.8.9' );
			return $columns;
		}

		/**
		 * Validates user fields updated via the REST API.
		 *
		 * @param  boolean $valid The current validity boolean.
		 * @param  integer $value The value of the field.
		 * @param  array   $field The field array.
		 * @return boolean|WP_Error
		 */
		public function validate_rest_value( $valid, $value, $field ) {
			if ( is_null( $value ) ) {
				return $valid;
			}

			$param = sprintf( '%s[%s]', $field['prefix'], $field['name'] );
			$data  = array( 'param' => $param );
			$value = is_array( $value ) ? $value : array( $value );

			$invalid_users      = array();
			$insufficient_roles = array();

			foreach ( $value as $user_id ) {
				$user_data = get_userdata( $user_id );
				if ( ! $user_data ) {
					$invalid_users[] = $user_id;
					continue;
				}

				if ( empty( $field['role'] ) ) {
					continue;
				}

				$has_roles = count( array_intersect( $field['role'], $user_data->roles ) );
				if ( ! $has_roles ) {
					$insufficient_roles[] = $user_id;
				}
			}

			if ( count( $invalid_users ) ) {
				$error = sprintf(
					/* translators: %s: field value */
					__( '%1$s must have a valid user ID.', 'secure-custom-fields' ),
					$param
				);
				$data['value'] = $invalid_users;
				return new WP_Error( 'rest_invalid_param', $error, $data );
			}

			if ( count( $insufficient_roles ) ) {
				$error = sprintf(
					/* translators: 1: field name, 2: role name */
					_n(
						'%1$s must have a user with the %2$s role.',
						'%1$s must have a user with one of the following roles: %2$s',
						count( $field['role'] ),
						'secure-custom-fields'
					),
					$param,
					count( $field['role'] ) > 1 ? implode( ', ', $field['role'] ) : $field['role'][0]
				);
				$data['value'] = $insufficient_roles;
				return new WP_Error( 'rest_invalid_param', $error, $data );
			}

			return $valid;
		}

		/**
		 * Return the schema array for the REST API.
		 *
		 * @param array $field
		 * @return array
		 */
		public function get_rest_schema( array $field ) {
			$schema = array(
				'type'     => array( 'integer', 'array', 'null' ),
				'required' => ! empty( $field['required'] ),
				'items'    => array(
					'type' => 'integer',
				),
			);

			if ( empty( $field['allow_null'] ) ) {
				$schema['minItems'] = 1;
			}

			if ( empty( $field['multiple'] ) ) {
				$schema['maxItems'] = 1;
			}

			return $schema;
		}

		/**
		 * @see \acf_field::get_rest_links()
		 * @param mixed          $value   The raw (unformatted) field value.
		 * @param integer|string $post_id
		 * @param array          $field
		 * @return array
		 */
		public function get_rest_links( $value, $post_id, array $field ) {
			$links = array();

			if ( empty( $value ) ) {
				return $links;
			}

			foreach ( (array) $value as $object_id ) {
				$links[] = array(
					'rel'        => 'acf:user',
					'href'       => rest_url( '/wp/v2/users/' . $object_id ),
					'embeddable' => true,
				);
			}

			return $links;
		}

		/**
		 * Apply basic formatting to prepare the value for default REST output.
		 *
		 * @param mixed          $value
		 * @param string|integer $post_id
		 * @param array          $field
		 * @return mixed
		 */
		public function format_value_for_rest( $value, $post_id, array $field ) {
			return acf_format_numerics( $value );
		}
	}


	// initialize
	acf_register_field_type( 'ACF_Field_User' );
endif; // class_exists check
