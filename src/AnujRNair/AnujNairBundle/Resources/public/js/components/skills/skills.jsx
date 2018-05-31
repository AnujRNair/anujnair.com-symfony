import React, { Component } from 'react';

import './skills.scss';

export default class Skills extends Component {
  shouldComponentUpdate() {
    return false;
  }

  render() {
    const icon = ['icon-thumbs-up', 'icon-spanner', 'icon-heart'];
    const items = {
      Languages: [
        'JavaScript, React, Redux',
        'Ruby, Rails',
        'PHP, Symfony2',
        'SQL, MySQL, MSSQL, TSQL',
        'CSS, SCSS, LESS, BEM, ITCSS'
      ],
      'Tools & Tech': [
        'Webpack, Babel',
        'Jest, Enzyme, Jasmine',
        'Memcached, Redis',
        'Git',
        'Selenium'
      ],
      Interests: [
        'Scalability & Performance',
        'User & Developer Experience',
        'Intuitive UI & UX',
        'Security',
        'Mentoring'
      ]
    };

    return Object.keys(items).map((section, index) => {
      const points = items[section].map(point => (
        <li className={`icon ${icon[index]}`} key={point}>
          {point}
        </li>
      ));

      return (
        <div className="skills" key={section}>
          <h4>{section}</h4>
          <ul className="list">{points}</ul>
        </div>
      );
    });
  }
}
