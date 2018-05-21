import React, { Component } from 'react';
import PropTypes from 'prop-types';

import './multidimensional-list.scss';

export default class MultidimensionalList extends Component {
  static propTypes = {
    list: PropTypes.objectOf(
      PropTypes.objectOf(
        PropTypes.shape({
          id: PropTypes.number.isRequired,
          title: PropTypes.string.isRequired,
          url: PropTypes.string.isRequired
        }).isRequired
      ).isRequired
    )
  };

  renderList() {
    const firstKeys = Object.keys(this.props.list);

    return firstKeys.map(f => {
      const secondKeys = Object.keys(this.props.list[f]);
      const second = secondKeys.map(s => {
        const items = this.props.list[f][s].map(item => (
          <li key={item.id}>
            <a href={item.url}>{item.title}</a>
          </li>
        ));

        return (
          <ul key={s}>
            <li>{s}</li>
            <ul>{items}</ul>
          </ul>
        );
      });

      return (
        <ul key={f}>
          <li>{f}</li>
          {second}
        </ul>
      );
    });
  }

  render() {
    if (Object.keys(this.props.list).length === 0) {
      return null;
    }

    return this.renderList();
  }
}
