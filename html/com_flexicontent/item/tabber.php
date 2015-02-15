
<!-- tabber start -->
<div class="fctabber fields_tabset" id='fcform_tabset_<?php echo $tabSetCnt; ?>' >
	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo JText::_( 'FLEXI_BASIC' ); ?> </h3>
		
		<?php $fset_lbl = $tags_displayed ? 'FLEXI_CATEGORIES_TAGS' : 'FLEXI_CATEGORIES';?>
		
		<div class="fcclear"></div>
		<fieldset class="basicfields_set" id="fcform_categories_tags_container">
			<legend>
				<?php echo JText::_( $fset_lbl ); ?>
			</legend>
			
			<label id="jform_catid-lbl" for="jform_catid" for_bck="jform_catid" class="flexi_label">
				<?php echo JText::_( 'FLEXI_CATEGORIES_MAIN' ); ?>
			</label>
			<div class="container_fcfield container_fcfield_name_catid">
				<?php echo $this->lists['catid']; ?>
				<span class="editlinktip hasTip" style="display:inline-block;" title="<?php echo htmlspecialchars(JText::_ ( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_ ( 'FLEXI_CATEGORIES_NOTES' ), ENT_COMPAT, 'UTF-8');?>">
				<?php echo $infoimage; ?>
				</span>
			</div>
			
			<?php if (1) : /* secondary categories always available in backend */ ?>
				
				<div class="fcclear"></div>
				<label id="jform_cid-lbl" for="jform_cid" for_bck="jform_cid" class="flexi_label">
					<?php echo JText::_( 'FLEXI_CATEGORIES' ); ?>
				</label>
				<div class="container_fcfield container_fcfield_name_catid">
					<?php echo $this->lists['cid']; ?>
				</div>
				
			<?php endif; ?>

			<?php if ( !empty($this->lists['featured_cid']) ) : ?>
				<div class="fcclear"></div>
				<label id="jform_featured_cid-lbl" for="jform_featured_cid" for_bck="jform_featured_cid" class="flexi_label">
					<?php echo JText::_( 'FLEXI_FEATURED_CATEGORIES' ); ?>
				</label>
				<div class="container_fcfield container_fcfield_name_featured_cid">
					<?php echo $this->lists['featured_cid']; ?>
				</div>
			<?php endif; ?>


			<div class="fcclear"></div>
			<span class="flexi_label">
				<?php echo $this->form->getLabel('featured'); ?>
				<br/><small><?php echo JText::_( 'FLEXI_JOOMLA_FEATURED_VIEW' ); ?></small>
			</span>
			<div class="container_fcfield container_fcfield_name_featured">
				<?php echo $this->lists['featured']; ?>
				<?php //echo $this->form->getInput('featured');?>
			</div>


			<?php if (1) : /* tags always available in backend */ ?>
				<?php
					$field = $this->fields['tags'];
					$label_tooltip = $field->description ? 'class="control-label hasTip flexi_label" title="'.'::'.htmlspecialchars($field->description, ENT_COMPAT, 'UTF-8').'"' : 'class="flexi_label"';
				?>
				<div class="fcclear"></div>
				<label id="jform_tag-lbl" for="jform_tag" <?php echo $label_tooltip; ?> >
					<?php echo $field->label; ?>
					<?php /*echo JText::_( 'FLEXI_TAGS' );*/ ?>
				</label>
				<div class="container_fcfield container_fcfield_name_tags">
					
					<div class="qf_tagbox" id="qf_tagbox">
						<ul id="ultagbox">
						<?php
							$nused = count($this->usedtags);
							for( $i = 0, $nused; $i < $nused; $i++ ) {
								$tag = $this->usedtags[$i];
								if ( $this->perms['cantags'] ) {
									echo '<li class="tagitem"><span>'.$tag->name.'</span>';
									echo '<input type="hidden" name="jform[tag][]" value="'.$tag->tid.'" /><a href="javascript:;" class="deletetag" onclick="javascript:deleteTag(this);" align="right" title="'.JText::_('FLEXI_DELETE_TAG').'"></a></li>';
								} else {
									echo '<li class="tagitem plain"><span>'.$tag->name.'</span>';
									echo '<input type="hidden" name="jform[tag][]" value="'.$tag->tid.'" /></li>';
								}
							}
						?>
						</ul>
					</div>

					<?php if ( $this->perms['cantags'] ) : ?>
					<div class="fcclear"></div>
					<div id="tags">
						<label for="input-tags">
							<?php echo JText::_( 'FLEXI_ADD_TAG' ); ?>
						</label>
						<input type="text" id="input-tags" name="tagname" tagid='0' tagname='' />
						<span id='input_new_tag' ></span>
						<span class="editlinktip hasTip" style="display:inline-block;" title="<?php echo htmlspecialchars(JText::_( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_( 'FLEXI_TAG_EDDITING_FULL' ), ENT_COMPAT, 'UTF-8');?>">
							<?php echo $infoimage; ?>
						</span>
					</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</fieldset>


	<?php if (FLEXI_FISH || FLEXI_J16GE) : ?>
		
		<div class="fcclear"></div>
		<fieldset class="basicfields_set" id="fcform_language_container">
			<legend>
				<?php echo JText::_( 'FLEXI_LANGUAGE' ); ?>
			</legend>
			
			<span class="flexi_label">
				<?php echo $this->form->getLabel('language'); ?>
			</span>
			
			<div class="container_fcfield container_fcfield_name_language">
				<?php echo $this->lists['languages']; ?>
			</div>

			<?php if ( $this->params->get('enable_translation_groups') ) : ?>

				<div class="fcclear"></div>
				<?php
					$label_tooltip = 'class="control-label hasTip flexi_label" title="'.'::'.htmlspecialchars(JText::_( 'FLEXI_ORIGINAL_CONTENT_ITEM_DESC' ), ENT_COMPAT, 'UTF-8').'"';
				?>
				<label id="jform_lang_parent_id-lbl" for="jform_lang_parent_id" <?php echo $label_tooltip; ?> >
					<?php echo JText::_( 'FLEXI_ORIGINAL_CONTENT_ITEM' );?>
				</label>
				
				<div class="container_fcfield container_fcfield_name_originalitem">
				<?php if ( $this->row->id  && (substr(flexicontent_html::getSiteDefaultLang(), 0,2) == substr($this->row->language, 0,2) || $this->row->language=='*') ) : ?>
					<br/><small><?php echo JText::_( $this->row->language=='*' ? 'FLEXI_ORIGINAL_CONTENT_ALL_LANGS' : 'FLEXI_ORIGINAL_TRANSLATION_CONTENT' );?></small>
					<input type="hidden" name="jform[lang_parent_id]" id="jform_lang_parent_id" value="<?php echo $this->row->id; ?>" />
				<?php else : ?>
					<?php
					if (1) { // currently selecting associated item, is always allowed in backend
						$jAp= JFactory::getApplication();
						$option = JRequest::getVar('option');
						$jAp->setUserState( $option.'.itemelement.langparent_item', 1 );
						$jAp->setUserState( $option.'.itemelement.type_id', $this->row->type_id);
						$jAp->setUserState( $option.'.itemelement.created_by', $this->row->created_by);
						//echo '<small>'.JText::_( 'FLEXI_ORIGINAL_CONTENT_IGNORED_IF_DEFAULT_LANG' ).'</small><br/>';
						echo $this->form->getInput('lang_parent_id');
					?>
						<span class="editlinktip hasTip" style="display:inline-block;" title="<?php echo htmlspecialchars(JText::_( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_( 'FLEXI_ORIGINAL_CONTENT_IGNORED_IF_DEFAULT_LANG' ), ENT_COMPAT, 'UTF-8');?>">
							<?php echo $infoimage; ?>
						</span>
					<?php
					} else {
						echo JText::_( 'FLEXI_ORIGINAL_CONTENT_ALREADY_SET' );
					}
					?>
				<?php endif; ?>
				</div>

<?php
//include('development_tmp.php');
?>

				<div class="fcclear"></div>
				<label id="langassocs-lbl" for="langassocs" class="flexi_label" >
					<?php echo JText::_( 'FLEXI_ASSOC_TRANSLATIONS' );?>
				</label>
				
				<div class="container_fcfield container_fcfield_name_langassocs">
				<?php
				if ( !empty($this->lang_assocs) )
				{
					$row_modified = 0;
					foreach($this->lang_assocs as $assoc_item) {
						if ($assoc_item->id == $this->row->lang_parent_id) {
							$row_modified = strtotime($assoc_item->modified);
							if (!$row_modified)  $row_modified = strtotime($assoc_item->created);
						}
					}
					
					foreach($this->lang_assocs as $assoc_item)
					{
						if ($assoc_item->id==$this->row->id) continue;
						
						$_link  = 'index.php?option=com_flexicontent&'.$task_items.'edit&cid='. $assoc_item->id;
						$_title = htmlspecialchars(JText::_( 'FLEXI_EDIT_ASSOC_TRANSLATION' ), ENT_COMPAT, 'UTF-8').':: ['. $assoc_item->lang .'] '. htmlspecialchars($assoc_item->title, ENT_COMPAT, 'UTF-8');
						echo "<a class='fc_assoc_translation editlinktip hasTip' target='_blank' href='".$_link."' title='".$_title."' >";
						//echo $assoc_item->id;
						if ( !empty($assoc_item->lang) && !empty($this->langs->{$assoc_item->lang}->imgsrc) ) {
							echo ' <img src="'.$this->langs->{$assoc_item->lang}->imgsrc.'" alt="'.$assoc_item->lang.'" />';
						} else if( !empty($assoc_item->lang) ) {
							echo $assoc_item->lang=='*' ? JText::_("All") : $assoc_item->lang;
						}
						
						$assoc_modified = strtotime($assoc_item->modified);
						if (!$assoc_modified)  $assoc_modified = strtotime($assoc_item->created);
						if ( $assoc_modified < $row_modified ) echo "(!)";
						echo "</a>";
					}
				}
				?>
				</div>
			<?php endif; /* IF enable_translation_groups */ ?>
			
		</fieldset>
	<?php endif; /* IF language */ ?>


	<?php if ( $this->perms['canright'] ) : ?>
		<?php
		$this->document->addScriptDeclaration("
			window.addEvent('domready', function() {
			var slideaccess = new Fx.Slide('tabacces');
			var slidenoaccess = new Fx.Slide('notabacces');
			slideaccess.hide();
				$$('fieldset.flexiaccess legend').addEvent('click', function(ev) {
					slideaccess.toggle();
					slidenoaccess.toggle();
					});
				});
			");
		?>
		
		<fieldset class="flexiaccess">
			<legend><?php echo JText::_( 'FLEXI_RIGHTS_MANAGEMENT' ); ?></legend>
			<div id="tabacces">
				<div id="access"><?php echo $this->form->getInput('rules'); ?></div>
			</div>
			<div id="notabacces">
			<?php echo JText::_( 'FLEXI_RIGHTS_MANAGEMENT_DESC' ); ?>
			</div>
		</fieldset>

	<?php endif; ?>

	</div> <!-- end tab -->



<?php
$type_lbl = $this->row->type_id ? JText::_( 'FLEXI_ITEM_TYPE' ) . ' : ' . $this->typesselected->name : JText::_( 'FLEXI_TYPE_NOT_DEFINED' );
?>
<?php if ($this->fields && $this->row->type_id) : ?>
	
	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo $type_lbl; ?> </h3>
		
		<?php
			$this->document->addScriptDeclaration("
				jQuery(document).ready(function() {
					jQuery('#jform_type_id').change(function() {
						if (jQuery('#jform_type_id').val() != '".$this->row->type_id."')
							jQuery('#fc-change-warning').css('display', 'block');
						else
							jQuery('#fc-change-warning').css('display', 'none');
					});
				});
			");
		?>
		
		<div class="fc_edit_container_full">
			
			<?php
			$hidden = array('fcloadmodule', 'fcpagenav', 'toolbar');
			$noplugin = '<div class="fc-mssg fc-warning">'. JText::_( 'FLEXI_PLEASE_PUBLISH_PLUGIN' ) .'</div>';
			$row_k = 0;
			foreach ($this->fields as $field)
			{
				// SKIP backend hidden fields from this listing
				if (
					($field->iscore && $field->field_type!='maintext')  ||
					$field->parameters->get('backend_hidden')  ||
					(in_array($field->field_type, $hidden) && empty($field->html)) ||
					in_array($field->formhidden, array(2,3))
				) continue;
				
				// check to SKIP (hide) field e.g. description field ('maintext'), alias field etc
				if ( $this->tparams->get('hide_'.$field->field_type) ) continue;
				
				$not_in_tabs = "";
				if ($field->field_type=='groupmarker') {
					echo $field->html;
					continue;
				} else if ($field->field_type=='coreprops') {
					// not used in backend (yet?)
					continue;
				}
				
				// Decide label classes, tooltip, etc
				$lbl_class = 'flexi_label';
				$lbl_title = '';
				// field has tooltip
				$edithelp = $field->edithelp ? $field->edithelp : 1;
				if ( $field->description && ($edithelp==1 || $edithelp==2) ) {
					 $lbl_class .= ' hasTip'.($edithelp==2 ? ' fc_tooltip_icon_bg' : '');
					 $lbl_title = '::'.htmlspecialchars($field->description, ENT_COMPAT, 'UTF-8');
				}
				// field is required
				$required = $field->parameters->get('required', 0 );
				if ($required)  $lbl_class .= ' required';
				
				// Some fields may force a container width ?
				$row_k = 1 - $row_k;
				$width = $field->parameters->get('container_width', '' );
				$width = !$width ? '' : 'width:' .$width. ($width != (int)$width ? 'px' : '');
				$container_class = "fcfield_row".$row_k." container_fcfield container_fcfield_id_".$field->id." container_fcfield_name_".$field->name;
				?>
				
				<div class='fcclear'></div>
				<label for="<?php echo (FLEXI_J16GE ? 'custom_' : '').$field->name;?>" for_bck="<?php echo (FLEXI_J16GE ? 'custom_' : '').$field->name;?>" class="<?php echo $lbl_class;?>" title="<?php echo $lbl_title;?>" >
					<?php echo $field->label; ?>
				</label>
				
				<div style="<?php echo $width; ?>;" class="<?php echo $container_class;?>" id="container_fcfield_<?php echo $field->id;?>">
					<?php echo ($field->description && $edithelp==3) ? '<div class="fc_mini_note_box">'.$field->description.'</div>' : ''; ?>
					
				<?php // CASE 1: CORE 'description' FIELD with multi-tabbed editing of joomfish (J1.5) or falang (J2.5+)
					if ($field->field_type=='maintext' && isset($this->row->item_translations) ) : ?>
					
					<!-- tabber start -->
					<div class="fctabber" style=''>
						<div class="tabbertab" style="padding: 0px;" >
							<h3 class="tabberheading"> <?php echo '- '.$itemlangname.' -'; // $t->name; ?> </h3>
							<?php
								$field_tab_labels = & $field->tab_labels;
								$field_html       = & $field->html;
								echo !is_array($field_html) ? $field_html : flexicontent_html::createFieldTabber( $field_html, $field_tab_labels, "");
							?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" style="padding: 0px;" >
									<h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
									<?php
									$field_tab_labels = & $t->fields->text->tab_labels;
									$field_html       = & $t->fields->text->html;
									echo !is_array($field_html) ? $field_html : flexicontent_html::createFieldTabber( $field_html, $field_tab_labels, "");
									?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					
				<?php elseif ( !is_array($field->html) ) : /* CASE 2: NORMAL FIELD non-tabbed */ ?>
					
					<?php echo isset($field->html) ? $field->html : $noplugin; ?>
					
				<?php else : /* MULTI-TABBED FIELD e.g textarea, description */ ?>
					
					<!-- tabber start -->
					<div class="fctabber">
					<?php foreach ($field->html as $i => $fldhtml): ?>
						<?php
							// Hide field when it has no label, and skip creating tab
							$not_in_tabs .= !isset($field->tab_labels[$i]) ? "<div style='display:none!important'>".$field->html[$i]."</div>" : "";
							if (!isset($field->tab_labels[$i]))	continue;
						?>
								
						<div class="tabbertab">
							<h3 class="tabberheading"> <?php echo $field->tab_labels[$i]; // Current TAB LABEL ?> </h3>
							<?php
								echo $not_in_tabs;      // Output hidden fields (no tab created), by placing them inside the next appearing tab
								$not_in_tabs = "";      // Clear the hidden fields variable
								echo $field->html[$i];  // Current TAB CONTENTS
							?>
						</div>
								
					<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					<?php echo $not_in_tabs;      // Output ENDING hidden fields, by placing them outside the tabbing area ?>
							
				<?php endif; /* END MULTI-TABBED FIELD */ ?>
				
				</div>
				
			<?php
			}
			?>
			
		</div>

	</div> <!-- end tab -->
	
<?php else : /* NO TYPE SELECTED */ ?>

	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo $type_lbl; ?> </h3>
		
		<div class="fc_edit_container_full">
			<?php if ($this->row->id == 0) : ?>
				<input name="jform[type_id_not_set]" value="1" type="hidden" />
				<div class="fc-mssg fc-note"><?php echo JText::_( 'FLEXI_CHOOSE_ITEM_TYPE' ); ?></div>
			<?php else : ?>
				<div class="fc-mssg fc-warning"><?php echo JText::_( 'FLEXI_NO_FIELDS_TO_TYPE' ); ?></div>
			<?php	endif; ?>
		</div>
		
	</div> <!-- end tab -->
	
<?php	endif; ?>


	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo JText::_('FLEXI_PUBLISHING'); ?> </h3>
		
		<?php
		$hide_style = $this->perms['canparams'] ? '' : 'visibility:hidden;';
		/*if (isset($fieldSet->description) && trim($fieldSet->description)) :
			echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
		endif;*/
		?>
		
		<fieldset class="flexi_params fc_edit_container_full" style="<?php echo $hide_style; ?>" >
			<div class='fc_mini_note_box'>
			<?php
				// Dates displayed in the item form, are in user timezone for J2.5, and in site's default timezone for J1.5
				$site_zone = JFactory::getApplication()->getCfg('offset');
				$user_zone = JFactory::getUser()->getParam('timezone', $site_zone);
				if (FLEXI_J16GE) {
					$tz = new DateTimeZone( $user_zone );
					$tz_offset = $tz->getOffset(new JDate()) / 3600;
				} else {
					$tz_offset = $site_zone;
				}
				$tz_info =  $tz_offset > 0 ? ' UTC +' . $tz_offset : ' UTC ' . $tz_offset;
				if (FLEXI_J16GE) $tz_info .= ' ('.$user_zone.')';
				echo JText::sprintf( FLEXI_J16GE ? 'FLEXI_DATES_IN_USER_TIMEZONE_NOTE' : 'FLEXI_DATES_IN_SITE_TIMEZONE_NOTE', '<br/>', $tz_info );
			?>
			</div>
			
			
			<?php /*if ($this->perms['isSuperAdmin']) :*/ ?>
			<div class="fcclear"></div><?php echo $this->form->getLabel('created_by'); ?>
			<div class="container_fcfield"><?php echo $this->form->getInput('created_by'); ?></div>
			<?php /*endif;*/ ?>
			
			<?php if ($this->perms['editcreationdate']) : ?>
			<div class="fcclear"></div><?php echo $this->form->getLabel('created'); ?>
			<div class="container_fcfield"><?php echo $this->form->getInput('created'); ?></div>
			<?php endif; ?>
			
			<div class="fcclear"></div><?php echo $this->form->getLabel('created_by_alias'); ?>
			<div class="container_fcfield"><?php echo $this->form->getInput('created_by_alias'); ?></div>
			
			<div class="fcclear"></div><?php echo $this->form->getLabel('publish_up'); ?>
			<div class="container_fcfield"><?php echo $this->form->getInput('publish_up'); ?></div>
			
			<div class="fcclear"></div><?php echo $this->form->getLabel('publish_down'); ?>
			<div class="container_fcfield"><?php echo $this->form->getInput('publish_down'); ?></div>
			
			<div class="fcclear"></div><?php echo $this->form->getLabel('access'); ?>
			<?php if ($this->perms['canacclvl']) :?>
				<div class="container_fcfield"><?php echo $this->form->getInput('access'); ?></div>
			<?php else :?>
				<div class="container_fcfield"><span class="label"><?php echo $this->row->access_level; ?></span></div>
			<?php endif; ?>

		</fieldset>
		
	</div> <!-- end tab -->
	
	
	
	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo JText::_('FLEXI_META_SEO'); ?> </h3>
		
		<?php
		//echo $this->form->getLabel('metadesc');
		//echo $this->form->getInput('metadesc');
		//echo $this->form->getLabel('metakey');
		//echo $this->form->getInput('metakey');
		?>
		
		<fieldset class="panelform params_set">
			<legend>
				<?php echo JText::_( 'FLEXI_META' ); ?>
			</legend>
			
			<div class="fcclear"></div>
			<?php echo $this->form->getLabel('metadesc'); ?>
			
			<div class="container_fcfield">
				
				<?php	if ( isset($this->row->item_translations) ) : ?>
					
					<!-- tabber start -->
					<div class="fctabber" style='display:inline-block;'>
						<div class="tabbertab" style="padding: 0px;" >
							<h3 class="tabberheading"> <?php echo '-'.$itemlang.'-'; // $t->name; ?> </h3>
							<?php echo $this->form->getInput('metadesc');?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" style="padding: 0px;" >
									<h3 class="tabberheading"> <?php echo $t->shortcode; // $t->name; ?> </h3>
									<?php
									$ff_id = 'jfdata_'.$t->shortcode.'_metadesc';
									$ff_name = 'jfdata['.$t->shortcode.'][metadesc]';
									?>
									<textarea id="<?php echo $ff_id; ?>" class="inputbox" rows="3" cols="46" name="<?php echo $ff_name; ?>"><?php echo @$t->fields->metadesc->value; ?></textarea>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- tabber end -->
				
				<?php else : ?>
					<?php echo $this->form->getInput('metadesc'); ?>
				<?php endif; ?>
				
			</div>
			
			<div class="fcclear"></div>
			<?php echo $this->form->getLabel('metakey'); ?>
			
			<div class="container_fcfield">
				<?php	if ( isset($this->row->item_translations) ) :?>
					
					<!-- tabber start -->
					<div class="fctabber" style='display:inline-block;'>
						<div class="tabbertab" style="padding: 0px;" >
							<h3 class="tabberheading"> <?php echo '-'.$itemlang.'-'; // $t->name; ?> </h3>
							<?php echo $this->form->getInput('metakey');?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" style="padding: 0px;" >
									<h3 class="tabberheading"> <?php echo $t->shortcode; // $t->name; ?> </h3>
									<?php
									$ff_id = 'jfdata_'.$t->shortcode.'_metakey';
									$ff_name = 'jfdata['.$t->shortcode.'][metakey]';
									?>
									<textarea id="<?php echo $ff_id; ?>" class="inputbox" rows="3" cols="46" name="<?php echo $ff_name; ?>"><?php echo @$t->fields->metakey->value; ?></textarea>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					
				<?php else : ?>
					<?php echo $this->form->getInput('metakey'); ?>
				<?php endif; ?>
				
				</div>

			<?php foreach($this->form->getGroup('metadata') as $field): ?>
				<div class="fcclear"></div>
				<?php if ($field->hidden): ?>
					<span style="visibility:hidden !important;">
						<?php echo $field->input; ?>
					</span>
				<?php else: ?>
					<?php echo $field->label; ?>
					<div class="container_fcfield">
						<?php echo $field->input;?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</fieldset>
		
		<fieldset class="panelform params_set">
			<legend>
				<?php echo JText::_( 'FLEXI_SEO' ); ?>
			</legend>
			
			<?php foreach ($this->form->getFieldset('params-seoconf') as $field) : ?>
				<div class="fcclear"></div>
				<?php echo $field->label; ?>
				<div class="container_fcfield">
					<?php echo $field->input;?>
				</div>
			<?php endforeach; ?>
			
		</fieldset>
		
	</div> <!-- end tab -->
	
	
	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo JText::_('FLEXI_DISPLAYING'); ?> </h3>
		
		<?php //echo JHtml::_('sliders.start','plugin-sliders-'.$this->row->id, array('useCookie'=>1)); ?>

		<?php
			$fieldSets = $this->form->getFieldsets('attribs');
			foreach ($fieldSets as $name => $fieldSet) :
				if ( $name=='themes' || $name=='params-seoconf'  || $name=='images' ||  $name=='urls' ) continue;

				//$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_'.$name.'_FIELDSET_LABEL';
				//echo JHtml::_('sliders.panel', JText::_($label), $name.'-options');
				?>
				<fieldset class="flexi_params panelform">
					<?php foreach ($this->form->getFieldset($name) as $field) : ?>
						<div class="fcclear"></div>
						<?php echo $field->label; ?>
						<?php if (strlen(trim($field->input))) :?>
							<div class="container_fcfield">
								<?php echo $field->input; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</fieldset>
		<?php endforeach; ?>

		<?php	//echo JHtml::_('sliders.end'); ?>
		
	</div> <!-- end tab -->
	
	
	<?php 
	// *********************
	// JOOMLA IMAGE/URLS TAB
	// *********************
	if (JComponentHelper::getParams('com_content')->get('show_urls_images_backend', 0) ) : ?>
		<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
			<h3 class="tabberheading"> <?php echo JText::_('Compatibility'); ?> </h3>
			
			<?php
			$fields_grps_compatibility = array('images', 'urls');
			foreach ($fields_grps_compatibility as $name => $fields_grp_name) :
			?>
			
			<fieldset class="flexi_params fc_edit_container_full">
				<?php foreach ($this->form->getGroup($fields_grp_name) as $field) : ?>
					<div class="fcclear"></div>
					<?php if ($field->hidden): ?>
						<span style="visibility:hidden !important;">
							<?php echo $field->input; ?>
						</span>
					<?php else: ?>
						<?php echo $field->label; ?>
						<div class="container_fcfield">
							<?php echo $field->input;?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</fieldset>
			
			<?php endforeach; ?>
			
		</div>
	<?php endif;
	?>
	
	
	
	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo JText::_('FLEXI_TEMPLATE'); ?> </h3>
		
		<fieldset class="fc_edit_container_full">
			<div class="fc-note fc-mssg-inline" style="margin: 8px 0px!important;">
			<?php
				echo JText::_( 'FLEXI_PARAMETERS_LAYOUT_EXPLANATION' ) ;
				$type_default_layout = $this->tparams->get('ilayout');
			?>
				<br/><br/>
				<ol style="margin:0 0 0 16px; padding:0;">
					<li style="margin:0; padding:0;"> Select TEMPLATE layout </li>
					<li style="margin:0; padding:0;"> Open slider with TEMPLATE (layout) PARAMETERS </li>
				</ol>
				<br/>
				<b>NOTE:</b> Common method for -displaying- fields is by <b>editing the template layout</b> in template manager and placing the fields into <b>template positions</b>
			</div>
			
			<?php foreach($this->form->getFieldset('themes') as $field): ?>
				<div class="fcclear"></div>
				<?php if ($field->hidden): ?>
					<span style="visibility:hidden !important;">
						<?php echo $field->input; ?>
					</span>
				<?php else: ?>
					<?php echo $field->label; ?>
					<div class="container_fcfield">
						<?php echo $field->input;?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>

			<div class="fcclear"></div>
			<span class="fc-success fc-mssg-inline" id='__content_type_default_layout__'>
				<?php echo JText::sprintf( 'FLEXI_USING_CONTENT_TYPE_LAYOUT', $type_default_layout ); ?>
				<?php echo "<br/><br/>". JText::_( 'FLEXI_RECOMMEND_CONTENT_TYPE_LAYOUT' ); ?>
			</span>
			<div class="fcclear"></div>
			
			<?php
			echo JHtml::_('sliders.start','theme-sliders-'.$this->form->getValue("id"), array('useCookie'=>1));
			$groupname = 'attribs';  // Field Group name this is for name of <fields name="..." >
			
			foreach ($this->tmpls as $tmpl) :
				$fieldSets = $tmpl->params->getFieldsets($groupname);
				foreach ($fieldSets as $fsname => $fieldSet) :
					$label = !empty($fieldSet->label) ? $fieldSet->label : JText::_( 'FLEXI_PARAMETERS_THEMES_SPECIFIC' ) . ' : ' . $tmpl->name;
					echo JHtml::_('sliders.panel',JText::_($label), $tmpl->name.'-'.$fsname.'-options');
					if (isset($fieldSet->description) && trim($fieldSet->description)) :
						echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
					endif;
					?>
					<fieldset class="panelform">
						<?php foreach ($tmpl->params->getFieldset($fsname) as $field) :
							$fieldname =  $field->__get('fieldname');
							$value = $tmpl->params->getValue($fieldname, $groupname, $this->row->itemparams->get($fieldname));
							echo $tmpl->params->getLabel($fieldname, $groupname);
							echo
								str_replace('jform_attribs_', 'jform_layouts_'.$tmpl->name.'_', 
									str_replace('[attribs]', '[layouts]['.$tmpl->name.']',
										$tmpl->params->getInput($fieldname, $groupname, $value)
									)
								);
						endforeach; ?>
					</fieldset>
				<?php endforeach; ?>
			<?php endforeach; ?>
				
			<?php echo JHtml::_('sliders.end'); ?>

		</fieldset>
		
	</div> <!-- end tab -->