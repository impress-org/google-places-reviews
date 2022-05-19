import { __ } from '@wordpress/i18n';
import StarRating from '../StarRating';
import BlankAvatar from '../../images/blank-avatar.png';
import { Icon } from '@wordpress/components';

export default function Review( { index, review = [] } ) {

    return (
        <div className={'rbg-business-review'} key={index}>
            <div className={'rbg-business-review-google-icon'} />
            <div className={'rbg-business-review-user'}>
                <div className={'rbg-business-review-user-image'}>
                    {review.profile_photo_url && (
                        <img src={review.profile_photo_url} alt={review.author_name} />
                    )}
                    {!review.profile_photo_url && (
                        <img src={BlankAvatar} alt={review.author_name} />
                    )}

                </div>
                <div className={'rbg-business-review-user-name'}>
                    {review.author_name}
                </div>
            </div>
            <div className={'rbg-business-review-content'}>
                <div className={'rbg-business-review-content-rating'}>
                    <StarRating
                        overallRating={review.rating}
                        date={review.relative_time_description}
                    />
                </div>
                <div className={'rbg-business-review-content-text'}>
                    <p>{review.text}</p>
                    <div className={'rbg-business-review-content-readmore-wrap'}>
                        <a href={review.author_url} target={'_blank'}
                           className={'rbg-business-review-content-readmore'}>{__( 'Read more', 'google-places-reviews' )}
                            <Icon icon={'external'} size={'100'} /></a>
                    </div>
                </div>
            </div>
        </div>
    );

}
