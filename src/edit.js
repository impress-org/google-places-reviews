import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl, SelectControl, Spinner, Button } from '@wordpress/components';
import { Fragment, useState, createRef, useEffect } from '@wordpress/element';

import './editor.scss';

import GoogleLogo from './images/google-logo.svg';
import { dispatch, useSelect } from '@wordpress/data';

/**
 * Edit function.
 *
 *  @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const { placeId, preview } = attributes;

    const locationRef = createRef();

    const [state, setState] = useState({
        isClearingCache: false,
        isLoadingPlace: false,
        cacheError: null,
        error: null,
    });

    const [googleApiKey, setGoogleApiKey] = useState('');
    const [googleConnected, setGoogleConnected] = useState(false);

    const siteSettings = useSelect((select) => {
        return select('core').getEntityRecord('root', 'site');
    }, []);

    useEffect(() => {
        if (siteSettings) {
            const { googleplacesreviews_options } = siteSettings;
            if (googleplacesreviews_options.google_places_api_key) {
                setGoogleApiKey(true);
                setGoogleConnected(true);
            }
        }
    }, [siteSettings]);

    const userIsAdmin = useSelect((select) => {
        return select('core').canUser('create', 'users');
    }, []);

    const testApiKey = (apiKey) => {
        // Fetch REST API to test key.
        apiFetch({ path: `/google-block/v1/profile?apiKey=${apiKey}&keyValidation=true` })
            .then((response) => {
                // 🔑 👍 Key is good. Save it.
                dispatch('core')
                    .saveEntityRecord('root', 'site', {
                        google_widget_settings: {
                            google_widget_fusion_api: apiKey,
                        },
                    })
                    .then(() => {
                        dispatch('core/notices').createErrorNotice(
                            __('🎉 Success! You have connected to the Yelp API.', 'google-widget-pro'),
                            {
                                isDismissible: true,
                                type: 'snackbar',
                            }
                        );
                        setGoogleConnected(true);
                    });
            })
            .catch((error) => {
                // 🔑 👎 Key is bad.
                const errorMessage = `${__('🙈️ Google API Error:', 'google-places-reviews')} ${error.message} ${__(
                    'Error Code:',
                    'google-places-reviews'
                )} ${error.code}`;
                dispatch('core/notices').createErrorNotice(errorMessage, {
                    isDismissible: true,
                    type: 'snackbar',
                });
            });
    };

    const isPlaceIdSet = () => attributes.placeId.trim().length !== 0;

    const handleCache = () => {
        setState({ isClearingCache: true });

        const placeId = attributes.placeId.substr(0, 25);

        fetch(ajaxurl, {
            method: 'POST',
            body: new URLSearchParams({
                action: 'gpr_free_clear_widget_cache',
                transient_id_1: `gpr_widget_api_${placeId}`,
                transient_id_2: `gpr_widget_options_${placeId}`,
            }),
        })
            .then(() => {
                setState({ isClearingCache: false });
            })
            .catch((error) => {
                console.log({ error });

                setState({
                    isClearingCache: false,
                    cacheError: __('Something went wrong', 'google-places-reviews'),
                });
            });
    };

    const handleSearch = (location) => {
        setAttributes({ location });

        if (location.trim().length < 3) {
            return;
        }

        const autocomplete = new google.maps.places.Autocomplete(locationRef.current, [attributes.place_type]);

        setState({ isLoadingPlace: true });

        autocomplete.addListener('place_changed', () => {
            const { placeId, name } = autocomplete.getPlace();

            if (!placeId) {
                return setState({
                    isLoadingPlace: false,
                    error: __('No place reference found for this location.', 'google-places-reviews'),
                });
            }

            setState({
                isLoadingPlace: false,
            });

            setAttributes({
                placeId,
                reference: placeId,
                location: name,
            });
        });
    };

    /**
     * Detect Google Maps API Authentication Error
     *
     * Google Authentication Callback in case there was an error
     *
     * @see: https://developers.google.com/maps/documentation/javascript/events#auth-errors
     * @see: https://developers.google.com/maps/documentation/javascript/events#auth-errors
     */
    window.gm_authFailure = () => {
        setState({ error: window.gpr_ajax_object.i18n.google_auth_error });
    };

    return (
        <Fragment>
            <Fragment>
                <InspectorControls>
                    {userIsAdmin && (
                        <Fragment>
                            <PanelBody
                                title={__('Reviews Block for Google', 'google-places-reviews')}
                                initialOpen={true}
                            >
                                {isPlaceIdSet() && (
                                    <div className="set-business">
                                        <p>
                                            <strong>{__('Place Set', 'google-places-reviews')}:</strong>
                                            {__(
                                                'You have successfully set the place for this widget. To switch the place use the Location Lookup field above.',
                                                'google-places-reviews'
                                            )}
                                        </p>

                                        <TextControl
                                            disabled
                                            name="place_id"
                                            label={__('Location', 'google-places-reviews')}
                                            value={attributes.location}
                                            help={__(
                                                "This is the name of the place returned by Google's Places API.",
                                                'google-places-reviews'
                                            )}
                                        />

                                        <TextControl
                                            disabled
                                            name="place_id"
                                            label={__('Location Place ID', 'google-places-reviews')}
                                            value={attributes.placeId}
                                            help={__(
                                                "This is the name of the place returned by Google's Places API.",
                                                'google-places-reviews'
                                            )}
                                        />
                                    </div>
                                )}
                            </PanelBody>

                            {isPlaceIdSet() && (
                                <Fragment>
                                    <PanelBody
                                        title={__('Review Options', 'google-places-reviews')}
                                        initialOpen={false}
                                    >
                                        <p className="pro-field">
                                            <TextControl
                                                disabled
                                                label={__('Minimum Review Rating', 'google-places-reviews')}
                                                value="No filter"
                                                help={__(
                                                    'PRO FEATURE: Filter bad reviews to prevent them from displaying. Please note that the Google Places API only allows for up to 5 total reviews displayed per location. This option may limit the total number further.',
                                                    'google-places-reviews'
                                                )}
                                            />
                                        </p>

                                        <SelectControl
                                            label={__('Limit Number of Reviews', 'google-places-reviews')}
                                            value={attributes.review_limit}
                                            options={[
                                                { label: 1, value: 1 },
                                                { label: 2, value: 2 },
                                                { label: 3, value: 3 },
                                                { label: 4, value: 4 },
                                                { label: 5, value: 5 },
                                            ]}
                                            onChange={(review_limit) => setAttributes({ review_limit })}
                                            help={__(
                                                'Limit the number of reviews displayed for this location to a set number.',
                                                'google-places-reviews'
                                            )}
                                        />
                                    </PanelBody>

                                    <PanelBody
                                        title={__('Display Options', 'google-places-reviews')}
                                        initialOpen={false}
                                    >
                                        <SelectControl
                                            label={__('Widget Theme', 'google-places-reviews')}
                                            value={attributes.widget_style}
                                            options={[
                                                {
                                                    label: __('Bare Bones', 'google-places-reviews'),
                                                    value: 'Bare Bones',
                                                },
                                                {
                                                    label: __('Minimal Light', 'google-places-reviews'),
                                                    value: 'Minimal Light',
                                                },
                                                {
                                                    label: __('Minimal Dark', 'google-places-reviews'),
                                                    value: 'Minimal Dark',
                                                },
                                                {
                                                    label: __('Shadow Light', 'google-places-reviews'),
                                                    value: 'Shadow Light',
                                                },
                                                {
                                                    label: __('Shadow Dark', 'google-places-reviews'),
                                                    value: 'Shadow Dark',
                                                },
                                            ]}
                                            onChange={(widget_style) => setAttributes({ widget_style })}
                                            help={__(
                                                "Choose from a set of predefined widget styles. Want to style your own? Set it to 'Bare Bones' for easy CSS styling.",
                                                'google-places-reviews'
                                            )}
                                        />

                                        <ToggleControl
                                            label={__('Hide Business Information', 'google-places-reviews')}
                                            checked={attributes.hide_header}
                                            onChange={(hide_header) => setAttributes({ hide_header })}
                                            help={__(
                                                'Disable the main business information profile image, name, overall rating. Useful for displaying only ratings in the widget.',
                                                'google-places-reviews'
                                            )}
                                        />

                                        <ToggleControl
                                            label={__('Hide "x out of 5 stars" text', 'google-places-reviews')}
                                            checked={attributes.hide_out_of_rating}
                                            onChange={(hide_out_of_rating) => setAttributes({ hide_out_of_rating })}
                                            help={__(
                                                "Hide the text the appears after the star image displaying 'x out of 5 stars'. The text will still be output because it is important for SEO but it will be hidden with CSS.",
                                                'google-places-reviews'
                                            )}
                                        />

                                        <ToggleControl
                                            label={__('Hide Google logo', 'google-places-reviews')}
                                            checked={attributes.hide_google_image}
                                            onChange={(hide_google_image) => setAttributes({ hide_google_image })}
                                            help={__(
                                                'Prevent the Google logo from displaying in the reviews widget.',
                                                'google-places-reviews'
                                            )}
                                        />
                                    </PanelBody>

                                    <PanelBody
                                        title={__('Advanced Options', 'google-places-reviews')}
                                        initialOpen={false}
                                    >
                                        <SelectControl
                                            label={__('Cache Data', 'google-places-reviews')}
                                            value={attributes.cache}
                                            options={[
                                                { label: __('None', 'google-places-reviews'), value: 'None' },
                                                { label: __('1 Hour', 'google-places-reviews'), value: '1 Hour' },
                                                { label: __('3 Hour', 'google-places-reviews'), value: '3 Hour' },
                                                { label: __('6 Hour', 'google-places-reviews'), value: '6 Hour' },
                                                { label: __('12 Hour', 'google-places-reviews'), value: '12 Hour' },
                                                { label: __('1 Day', 'google-places-reviews'), value: '1 Day' },
                                                { label: __('2 Days', 'google-places-reviews'), value: '2 Days' },
                                                { label: __('1 Week', 'google-places-reviews'), value: '1 Week' },
                                            ]}
                                            onChange={(cache) => setAttributes({ cache })}
                                            help={__(
                                                'Caching data will save Google Place data to your database in order to speed up response times and conserve API requests. The suggested settings is 1 Day.',
                                                'google-places-reviews'
                                            )}
                                        />

                                        <p>
                                            {state.isClearingCache ? (
                                                <p>
                                                    <Spinner />
                                                </p>
                                            ) : (
                                                <button className="button button-primary" onClick={handleCache}>
                                                    {__('Clear cache', 'google-places-reviews')}
                                                </button>
                                            )}

                                            {state.cacheError && (
                                                <div className="gpr-alert gpr-error">{state.cacheError}</div>
                                            )}
                                        </p>

                                        <br />

                                        <ToggleControl
                                            label={__('Disable Title Output', 'google-places-reviews')}
                                            checked={attributes.disable_title_output}
                                            onChange={(disable_title_output) => setAttributes({ disable_title_output })}
                                            help={__(
                                                "The title output is content within the 'Widget Title' field above. Disabling the title output may be useful for some themes.",
                                                'google-places-reviews'
                                            )}
                                        />

                                        <ToggleControl
                                            label={__('Open Links in New Window', 'google-places-reviews')}
                                            checked={attributes.target_blank}
                                            onChange={(target_blank) => setAttributes({ target_blank })}
                                            help={__(
                                                "This option will add target='_blank' to the widget's links. This is useful to keep users on your website.",
                                                'google-places-reviews'
                                            )}
                                        />

                                        <ToggleControl
                                            label={__('No Follow Links', 'google-places-reviews')}
                                            checked={attributes.no_follow}
                                            onChange={(no_follow) => setAttributes({ no_follow })}
                                            help={__(
                                                "This option will add rel='nofollow' to the widget's outgoing links. This option may be useful for SEO.",
                                                'google-places-reviews'
                                            )}
                                        />
                                    </PanelBody>
                                </Fragment>
                            )}
                        </Fragment>
                    )}
                </InspectorControls>
            </Fragment>

            <Fragment>
                <div {...useBlockProps()}>
                    {!googleConnected && !placeId && (
                        <div id={'rbg-admin-welcome-wrap'}>
                            <div className="rbg-admin-welcome-content-wrap">
                                <img
                                    className={'rbg-admin-google-logo'}
                                    src={GoogleLogo}
                                    alt={__('Google Logo', 'google-places-reviews')}
                                />
                                <div id={'yelp-block-admin-lottie-api'}></div>
                                <h2 className={'rbg-admin-google-welcome-heading'}>
                                    {__(
                                        'Welcome to the Reviews Block for Google! Let’s get started.',
                                        'google-places-reviews'
                                    )}
                                </h2>
                                <p className={'rbg-admin-google-welcome-text'}>
                                    {__(
                                        'This plugin requires a Google Places API key to get started. Don’t worry! It’s easy to get one.',
                                        'google-places-reviews'
                                    )}
                                </p>
                                <TextControl
                                    value={googleApiKey}
                                    type={'password'}
                                    help={
                                        <>
                                            {__(
                                                'Please enter your API key to use this block.',
                                                'google-places-reviews'
                                            )}{' '}
                                            <a
                                                href="https://wpbusinessreviews.com/documentation/platforms/google/"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                {__(
                                                    'Learn how to create a Google Places API key',
                                                    'google-places-reviews'
                                                )}
                                            </a>
                                            {'.'}
                                        </>
                                    }
                                    onChange={(newApiKey) => {
                                        setGoogleApiKey(newApiKey);
                                    }}
                                />
                                <Button className={'rbg-admin-button'} isPrimary onClick={() => testApiKey(yelpApiKey)}>
                                    {__('Save API Key', 'google-places-reviews')}
                                </Button>
                            </div>
                        </div>
                    )}
                    {googleConnected && !placeId && (
                        <div>
                            <TextControl
                                name="place_id"
                                label={__('Location Lookup', 'google-places-reviews')}
                                value={attributes.location}
                                onChange={(location) => handleSearch(location)}
                                ref={locationRef}
                                help={__(
                                    'Enter the name of your business in this field to retrieve its Google Place ID. If no information is returned there you may have a conflict with another plugin or theme using Google Maps API.',
                                    'google-places-reviews'
                                )}
                            />

                            {state.isLoadingPlace && (
                                <p>
                                    <Spinner />
                                </p>
                            )}

                            <SelectControl
                                label={__('Place Type', 'google-places-reviews')}
                                value={attributes.place_type}
                                options={[
                                    { label: __('All', 'google-places-reviews'), value: 'all' },
                                    { label: __('Addresses', 'google-places-reviews'), value: 'address' },
                                    {
                                        label: __('Establishments', 'google-places-reviews'),
                                        value: 'establishment',
                                    },
                                    { label: __('Regions', 'google-places-reviews'), value: '(regions)' },
                                ]}
                                onChange={(place_type) => setAttributes({ place_type })}
                                help={__(
                                    'Specify how you would like to lookup your Google Places.',
                                    'google-places-reviews'
                                )}
                            />
                        </div>
                    )}
                </div>
            </Fragment>
        </Fragment>
    );
}
