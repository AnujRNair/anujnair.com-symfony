import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { ContactForm } from '@anujnair/js/types/form';

import Jumbotron from '@anujnair/js/components/jumbotron/jumbotron';
import Skills from '@anujnair/js/components/skills/skills';
import Contact from '@anujnair/js/components/contact/contact';

export default class AboutIndex extends Component {
  static propTypes = {
    years: PropTypes.number.isRequired
  };

  renderText() {
    return (
      <Fragment>
        <h3>About Anuj Nair</h3>
        <p>
          For the past {this.props.years} years, I have been a Frontend and
          Backend engineer, creating websites and applications for a variety of
          different industries, including eCommerce, media, social, & forums. My
          role has evolved from being a Freelancer to a Senior Developer, to a
          Software Engineering Lead & Manager.
        </p>
        <p>
          Described as talented and as a tireless teacher, I have a passion for
          delivering high quality results. I consider the bigger picture whilst
          simultaneously paying exceptional attention to the little details, and
          always strive to better the people around me.
        </p>
        <h3>About this site</h3>
        <p>
          AnujNair.com is my own personal website. It acts as an outlet for me
          to blog about my latest findings, show off what I have been working
          on, and act as a general playground for me to try new technologies and
          implement my crazy new ideas!
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

export class AboutAside extends Component {
  static propTypes = {
    form: ContactForm
  };

  renderFindMe() {
    const items = [
      {
        icon: 'icon-gh-full',
        name: 'GitHub',
        link: 'https://github.com/AnujRNair/'
      },
      {
        icon: 'icon-so',
        name: 'Stack Overflow',
        link: 'https://github.com/AnujRNair/'
      },
      {
        icon: 'icon-li',
        name: 'LinkedIn',
        link: 'https://github.com/AnujRNair/'
      },
      {
        icon: 'icon-fb',
        name: 'Facebook',
        link: 'https://github.com/AnujRNair/'
      },
      {
        icon: 'icon-t',
        name: 'Twitter',
        link: 'https://github.com/AnujRNair/'
      },
      {
        icon: 'icon-gp',
        name: 'Google+',
        link: 'https://github.com/AnujRNair/'
      },
      {
        icon: 'icon-earth-full',
        name: 'AnujNair.com',
        link: 'https://github.com/AnujRNair/'
      }
    ];

    const jsxItems = items.map(item => (
      <li key={item.name} className={`icon icon--absolute ${item.icon}`}>
        <a href={item.link}>{item.name}</a>
      </li>
    ));

    return (
      <Fragment>
        <h3>Also find me here:</h3>
        <ul className={'list'}>{jsxItems}</ul>
      </Fragment>
    );
  }

  renderLearnMore() {
    return (
      <Fragment>
        <h3>Learn More</h3>
        <ul className={'list'}>
          <li className={'icon icon-pencil icon--absolute'}>
            <a href={'/blog'}>View my blog posts</a>
          </li>
          <li className={'icon icon-earth-full icon--absolute'}>
            <a href={'/portfolio'}>View my work</a>
          </li>
        </ul>
      </Fragment>
    );
  }

  render() {
    return (
      <Fragment>
        <Contact form={this.props.form} />
        {this.renderFindMe()}
        {this.renderLearnMore()}
      </Fragment>
    );
  }
}
