import React, { Component } from 'react';
import PropTypes from 'prop-types';

export default class Error extends Component {
  static propTypes = {
    message: PropTypes.string.isRequired,
    statusCode: PropTypes.number.isRequired
  };

  shouldComponentUpdate() {
    return false;
  }

  renderBlocks() {
    const blocks = [
      {
        icon: 'icon-pencil',
        color: 'green',
        header: 'Check out my blog',
        bullets: [
          {
            text: "It's interesting, I promise. (I hope.)",
            url: '/blog'
          }
        ]
      },
      {
        icon: 'icon-earth',
        color: 'yellow',
        header: 'Visit my portfolio',
        bullets: [
          {
            text: 'See my recent work',
            url: '/portfolio'
          }
        ]
      },
      {
        icon: 'icon-man',
        color: 'blue',
        header: 'Learn about me',
        bullets: [
          {
            text: 'Find out about me, or contact me',
            url: '/contact'
          }
        ]
      }
    ];

    return blocks.map(block => {
      const bullets = block.bullets.map(bullet => (
        <li className={`icon icon--absolute ${block.icon}`} key={bullet.text}>
          <a href={bullet.url}>{bullet.text}</a>
        </li>
      ));

      return (
        <div
          className={`error__block error__block--${block.color}`}
          key={block.header}
        >
          <h4>{block.header}</h4>
          <ul className="list">{bullets}</ul>
        </div>
      );
    });
  }

  render() {
    return (
      <div className="error">
        <h2>I couldn&apos;t find what you were looking for!</h2>
        <h3>{this.props.message}</h3>
        <h4>That&apos;s a {this.props.statusCode}</h4>

        <div className="error__blocks">
          <p>
            Let&apos;s get you back on the right track, Maybe one of the
            following can help you?
          </p>
          {this.renderBlocks()}
        </div>
      </div>
    );
  }
}
