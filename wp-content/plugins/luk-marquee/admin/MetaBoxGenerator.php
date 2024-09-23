<?php
	
	class MetaBoxGenerator
	{
		private $keyPrefix;
		private $textDomain;
		private $metaBoxId;
		private $metaBoxTitle;
		private $metaBoxScreen;
		private $metaBoxDescription = '';
		private $metaBoxContext = 'normal';
		private $metaBoxPriority = 'default';
		public $fields;
		
		/**
		 * @param $keyPrefix
		 * @param $textDomain
		 * @param $metaBoxId
		 * @param $metaBoxTitle
		 * @param $metaBoxDescription
		 * @param $metaBoxScreen
		 */
		public function __construct($keyPrefix, $metaBoxId, $metaBoxTitle, $metaBoxScreen, $textDomain)
		{
			$this->keyPrefix = $keyPrefix;
			$this->textDomain = $textDomain;
			$this->metaBoxId = $metaBoxId;
			$this->metaBoxTitle = $metaBoxTitle;
			$this->metaBoxScreen = $metaBoxScreen;
		}
		
		
		public function addMetaBox()
		{
			add_meta_box(
				$this->metaBoxId,
				$this->metaBoxTitle,
				[ $this, 'addMetaBoxCallback' ],
				$this->metaBoxScreen,
				$this->metaBoxContext,
				$this->metaBoxPriority,
			);
		}
		
		public function addMetaBoxCallback($post)
		{
			wp_nonce_field($this->keyPrefix.'nonce', $this->keyPrefix.'nonce_field');
			echo '<div class="mbg-box '.$this->metaBoxId.'">';
			echo '<div class="mbg-description">'.__($this->metaBoxDescription, $this->textDomain).'</div>';
			$this->renderFields();
			echo '</div>';
		}
		
		private function renderFields()
		{
			foreach ($this->fields as $field)
			{
				echo '<div class="mbg-field '.$field['id'].' '.$field['class'].'">';
				switch ( $field['type'] ) {
					case 'shortcode':
						$this->renderFieldShortcode($field);
						break;
					case 'checkbox':
						$this->renderFieldCheckbox($field);
						break;
					case 'html':
						$this->renderFieldHTML($field);
						break;
					case 'number':
						$this->renderFieldNumber($field);
						break;
					case 'select':
						$this->renderFieldSelect($field);
						break;
					default:
						$this->renderFieldDefault($field);
				}
				echo '</div>';
			}
		}
		
		private function renderFieldDefault($field)
		{
			$this->renderFieldLabel($field);
			printf(
				'<div class="mbg-input-wrapper -default"><input id="%s" name="%s" type="text" value="%s" placeholder="%s" %s ></div>',
				$this->getFieldInputId($field),
				$this->getFieldInputId($field),
				$this->getFieldInputValue($field),
				$this->getFieldInputValue($field),
				$field['attr']
			);
		}
		
		private function renderFieldShortcode($field)
		{
			$this->renderFieldLabel($field);
			printf(
				'<div class="mbg-input-wrapper -shortcode"><input id="%s" name="%s" type="text" value="%s" readonly disabled ></div>',
				$this->getFieldInputId($field),
				$this->getFieldInputId($field),
				isset($_GET['post']) ? esc_attr('['.$field['shortcode-tag'].' id="'.$_GET['post'].'"]') : '',
			);
		}
		
		private function renderFieldHTML($field) {
			echo $field['html'];
		}
		
		private function renderFieldCheckbox($field)
		{
			$this->renderFieldLabel($field);
			printf(
				'<div class="mbg-input-wrapper -checkbox"><input type="checkbox" name="%s" id="%s" value="1"  %s  /></div>',
				$this->getFieldInputId($field),
				$this->getFieldInputId($field),
				$this->getFieldInputValue($field) == 1 ? 'checked' : ''
			);
		}
		
		private function renderFieldNumber($field)
		{
			$this->renderFieldDefault($field);
		}
		
		private function renderFieldSelect($field)
		{
			$this->renderFieldLabel($field);
			
			printf(
				'<div class="mbg-input-wrapper -select"><select name="%s" id="%s">',
				$this->getFieldInputId($field),
				$this->getFieldInputId($field),
			);
			
			foreach ($field['options'] as $value => $view)
			{
				printf(
					'<option value="%s" %s>%s</option>',
					$value,
					$this->getFieldInputValue($field) == $value ? 'selected' : '',
					$view,
				);
			}
			
			echo '</select></div>';
			
		}
		
		private function renderFieldLabel($field)
		{
			printf(
				'<label class="mbg-label" for="%s">%s</label>',
				$this->getFieldInputId($field),
				__($field['label'], $this->textDomain)
			);
		}
		
		private function getFieldInputId($field)
		{
			return $this->keyPrefix.$field['id'];
		}
		
		private function getFieldInputValue($field)
		{
			global $post;
			$input_id = $this->keyPrefix.$field['id'];
			if ( metadata_exists( 'post', $post->ID, $input_id ) )
			{
				$value = get_post_meta( $post->ID, $input_id, true );
			}
			else if ( isset( $field['default'] ) )
			{
				$value = $field['default'];
			} else
			{
				return '';
			}
			return str_replace( '\u0027', "'", $value );
		}
		
		public function savePost($post_id)
		{
			foreach ($this->fields as $field)
			{
				$fieldId = $this->getFieldInputId($field);
				switch ( $field['type'] ) {
					case 'html':
						break;
					case 'checkbox':
						update_post_meta( $post_id, $fieldId, isset( $_POST[ $fieldId ] ) ? $_POST[ $fieldId ] : 0 );
						break;
					default:
						if ( isset( $_POST[ $fieldId ] ) ) {
							$sanitized = sanitize_text_field( $_POST[ $fieldId ] );
							update_post_meta( $post_id, $fieldId, $sanitized );
						}
				}
			}
		}
		
		public function setKeyPrefix($keyPrefix){$this->keyPrefix = $keyPrefix;}
		public function setTextDomain($textDomain){$this->textDomain = $textDomain;}
		public function setMetaBoxId($metaBoxId){$this->metaBoxId = $metaBoxId;}
		public function setMetaBoxTitle($metaBoxTitle){$this->metaBoxTitle = $metaBoxTitle;}
		public function setMetaBoxDescription($metaBoxDescription){$this->metaBoxDescription = $metaBoxDescription;}
		public function setMetaBoxScreen($metaBoxScreen){$this->metaBoxScreen = $metaBoxScreen;}
		public function setMetaBoxContext($metaBoxContext){$this->metaBoxContext = $metaBoxContext;}
		public function setMetaBoxPriority($metaBoxPriority){$this->metaBoxPriority = $metaBoxPriority;}
		
		public function run()
		{
			add_action('add_meta_boxes', [ $this, 'addMetaBox' ]);
			add_action( 'save_post', [ $this, 'savePost' ] );
		}
		
	}