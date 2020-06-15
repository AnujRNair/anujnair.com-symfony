import React, { Fragment } from 'react';
import PropTypes from 'prop-types';

import { TagSummary as TagSummaryProps } from '@anujnair/js/types/tag';

function TagSummary({ header, highlightTagId, icon, list, urlPath }) {
  if (Object.keys(list).length === 0) {
    return null;
  }

  const iconClass = icon ? `icon icon--absolute ${icon}` : '';
  const items = Object.keys(list).map((idx) => {
    const item = list[idx];
    const highlightClass =
      highlightTagId === item.id ? 'list--highlighted' : '';

    return (
      <li className={`${iconClass} ${highlightClass}`} key={idx}>
        <a
          href={`/${urlPath}/${item.id}-${item.name
            .toLowerCase()
            .replace(' ', '-')}`}
        >
          {item.name}
          <em>({item.tagCount})</em>
        </a>
      </li>
    );
  });

  return (
    <Fragment>
      <h3>{header} Tags</h3>
      <ul className="list">{items}</ul>
    </Fragment>
  );
}

TagSummary.propTypes = {
  header: PropTypes.string.isRequired,
  highlightTagId: PropTypes.number,
  icon: PropTypes.string,
  list: TagSummaryProps.isRequired,
  urlPath: PropTypes.string.isRequired,
};

TagSummary.defaultProps = {
  highlightTagId: null,
  icon: null,
};

export default TagSummary;
