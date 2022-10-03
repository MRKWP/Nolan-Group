/**
 * This script modifies the core/gallery block
 *
 */
// import classnames
import classnames from 'classnames';

/**
* WordPress Dependencies
*/
const { __ } = wp.i18n;
const { addFilter } = wp.hooks;
const { Fragment, useState }	= wp.element;
const { InspectorControls }	= wp.editor;
const { createHigherOrderComponent } = wp.compose;
const { PanelBody, PanelRow, TextControl, ToggleControl } = wp.components;


const enableLightboxAddOnsToBlocks = [
    'core/gallery',
];

const addLightBoxAddOnsToBlocks = (settings, name ) => {

    // Do nothing if it's another block than our defined ones.
    if ( typeof settings.attributes !== 'undefined' && enableLightboxAddOnsToBlocks.includes( settings.name )  ) {
        settings.attributes = Object.assign( settings.attributes, {
            enableMrkLightBox: {
                type: 'boolean',
                default: false,
            },
            mrkLightBoxUniqueID: {
                type: 'string',
                default: '',
            },
        });
    }

    return settings;
}

/**
 * Add enable lightbox controls on Advanced Block Panel.
 *
 * @param {function} BlockEdit Block edit component.
 *
 * @return {function} BlockEdit Modified block edit component.
 */
const enableLightBoxAddOnControl = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {

        // Do nothing if it's another block than our defined ones.
        if ( ! enableLightboxAddOnsToBlocks.includes( props.name ) ) {
            return (
                <BlockEdit { ...props } />
            );
        }

        const {
            attributes,
            setAttributes,
            isSelected,
            clientId
        } = props;

        const {
            enableMrkLightBox, mrkLightBoxUniqueID
        } = attributes;

        setAttributes( { mrkLightBoxUniqueID :  'mrk-lightbox-' + clientId } );

        return (
            <Fragment>
                <BlockEdit {...props} />
                { isSelected &&
                <InspectorControls>
                    <PanelBody
                        title={ __( 'MRK Lightbox' ) }
                        initialOpen={ true }
                    >
                        <ToggleControl
                            label={ __( 'Activate' ) }
                            checked={ !! enableMrkLightBox }
                            onChange={ () => setAttributes( {  enableMrkLightBox: ! enableMrkLightBox } ) }
                            help={ !! enableMrkLightBox ? __( 'MRK Lightbox Enabled.' ) : __( 'MRK Lightbox Disabled.' ) }
                        />
                        {
                            enableMrkLightBox && (
                                <>
                                    <PanelRow>
                                        <fieldset>
                                            <TextControl
                                                label={__('MRK Lightbox Name', 'mrk-lightbox')}
                                                value={ mrkLightBoxUniqueID }
                                                placeholder={ __(
                                                    'Gallery Unique ID',
                                                    'mrk-lightbox'
                                                ) }
                                                onChange={ ( mrkLightBoxUniqueID ) => setAttributes( { mrkLightBoxUniqueID: mrkLightBoxUniqueID } ) }
                                            />
                                        </fieldset>
                                    </PanelRow>
                                </>
                            )
                        }
                    </PanelBody>
                </InspectorControls>
                }
            </Fragment>
        );
    };
}, 'enableLightBoxAddOnControl');

const modifyBlockSaveElement = ( extraProps, blockType, attributes ) => {

    const { enableMrkLightBox } = attributes;

    if (enableLightboxAddOnsToBlocks.includes(blockType.name)) {
        if( typeof enableMrkLightBox !== 'undefined'  && enableMrkLightBox) {

            const { enableMrkLightBoxThumbnails, mrkLightBoxUniqueID } = attributes

            extraProps['mrkwp-lightbox-enableMrkLightBox'] = enableMrkLightBox;
            extraProps[ 'mrkwp-lightbox-mrkLightBoxUniqueID' ] = mrkLightBoxUniqueID;

            extraProps.className = classnames( extraProps.className, 'mrkwp-lightbox' );
        }
    }

    return extraProps;
};

addFilter( 'blocks.registerBlockType', 'mrk-lightbox/mrk-lightbox-addon-attributes', addLightBoxAddOnsToBlocks );
addFilter( 'editor.BlockEdit',  'mrk-lightbox/mrk-lightbox-addon-control', enableLightBoxAddOnControl );
addFilter( 'blocks.getSaveContent.extraProps',  'mrk-lightbox/mrk-lightbox-addon-element', modifyBlockSaveElement );