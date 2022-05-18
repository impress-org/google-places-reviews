import { __ } from '@wordpress/i18n';

export default function StarRating( { overallRating, totalRatings = '', date = '', size = 'small' } ) {

    const dateFormatted = ( date ) => {
        const dateObj = new Date( date );
        const month = dateObj.toLocaleString( undefined, { month: 'long' } );
        const day = dateObj.getDate();
        const year = dateObj.getFullYear();
        return `${month} ${day}, ${year}`;
    };

    return (
        <div className={`rbg-business-stars-wrap rbg-business-stars-wrap-${size}`}>
            <span
                aria-label={`${overallRating} out of 5 stars`}
                className={`rbg-business-stars rbg-business-stars--${overallRating.toString().replace( '.', '-' )}  rbg-business-stars-${size}`}></span>
            {totalRatings && (
                <span
                    className={'rbg-business-stars-reviews'}>{totalRatings} {__( 'reviews', 'yelp-widget-pro' )}</span>
            )}
            {date && (
                <span className={'rbg-business-stars-date'}>{dateFormatted( date )}</span>
            )}

        </div>
    );
}
