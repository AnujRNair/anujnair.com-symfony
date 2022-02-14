import React, { Component, PureComponent, Fragment } from 'react';
import PropTypes from 'prop-types';

import { ContactForm } from '@anujnair/js/types/form';

import Jumbotron from '@anujnair/js/components/jumbotron/jumbotron';
import Skills from '@anujnair/js/components/skills/skills';
import Contact from '@anujnair/js/components/contact/contact';

export default class AboutIndex extends Component {
  static propTypes = {
    years: PropTypes.number.isRequired,
  };

  shouldComponentUpdate() {
    return false;
  }

  renderText() {
    return (
      <Fragment>
        <h3>About Anuj Nair</h3>
        <p>
          For the past {this.props.years} years, I have been a Frontend and
          Backend engineer, creating sites, applications and platforms for a
          variety of different industries. My role has evolved from being a
          Freelancer to a Senior Developer, to a Software Engineering Lead &
          Manager.
        </p>
        <p>
          I currently work at Slack Technologies as a Senior Staff Software
          Engineer, specializing in Frontend Performance and Architecture. My
          role is focused around making the user and developer experience as
          smooth, efficient and performant as can be.
        </p>
        <p>
          I have a passion for the open web and diving into new web technologies
          to deliver high quality applications. I always consider the bigger
          picture whilst simultaneously paying exceptional attention to the
          little details to make this happen.
        </p>
        <h3>About this site</h3>
        <p>
          AnujNair.com is my own personal website. It acts as an outlet for me
          to blog about some of my findings, and is a general playground for me
          to try new technologies and implement new ideas.
        </p>
      </Fragment>
    );
  }

  render() {
    return (
      <Fragment>
        <Jumbotron />
        {this.renderText()}
        <Skills />
      </Fragment>
    );
  }
}

export class AboutAside extends PureComponent {
  static propTypes = {
    form: ContactForm.isRequired,
    success: PropTypes.arrayOf(PropTypes.string.isRequired),
  };

  static defaultProps = {
    success: [],
  };

  renderFindMe() {
    const items = [
      {
        icon: 'icon-gh-full',
        name: 'GitHub',
        link: 'https://github.com/AnujRNair/',
      },
      {
        icon: 'icon-so',
        name: 'Stack Overflow',
        link: 'https://stackoverflow.com/users/1759688/anuj',
      },
      {
        icon: 'icon-li',
        name: 'LinkedIn',
        link: 'https://www.linkedin.com/in/anujrnair/',
      },
      {
        icon: 'icon-t',
        name: 'Twitter',
        link: 'https://twitter.com/AnujRNair',
      },
      {
        icon: 'icon-earth-full',
        name: 'AnujNair.com',
        link: 'https://anujnair.com',
      },
    ];

    const jsxItems = items.map((item) => (
      <li
        className={`icon icon--absolute ${item.icon}`}
        itemProp="sameAs"
        key={item.name}
      >
        <a href={item.link} rel="noopener noreferrer" target="_blank">
          {item.name}
        </a>
      </li>
    ));

    return (
      <Fragment>
        <h3>Also find me here:</h3>
        <ul
          className="list"
          itemScope
          itemType="http://schema.org/Organization"
        >
          {jsxItems}
        </ul>
      </Fragment>
    );
  }

  renderLearnMore() {
    return (
      <Fragment>
        <h3>Learn More</h3>
        <ul className="list">
          <li className="icon icon-pencil icon--absolute">
            <a href="/blog">View my blog posts</a>
          </li>
          <li className="icon icon-earth-full icon--absolute">
            <a href="/portfolio">View my work</a>
          </li>
        </ul>
      </Fragment>
    );
  }

  render() {
    return (
      <Fragment>
        <Contact form={this.props.form} success={this.props.success} />
        {this.renderFindMe()}
        {this.renderLearnMore()}
      </Fragment>
    );
  }
}
