import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { ContactForm } from '@anujnair/js/types/form';

import './contact.scss';

export default class Contact extends Component {
  static propTypes = {
    form: ContactForm.isRequired,
    success: PropTypes.arrayOf(PropTypes.string.isRequired)
  };

  static defaultProps = {
    success: []
  };

  constructor(props) {
    super(props);

    this.state = {
      name: props.form.values.name || '',
      email: props.form.values.email || '',
      subject: props.form.values.subject || '',
      contents: props.form.values.contents || ''
    };
  }

  updateContactFieldState = (prop, text) => {
    this.setState(() => ({
      [prop]: text
    }));
  };

  handleNameChange = e => this.updateContactFieldState('name', e.target.value);
  handleEmailChange = e =>
    this.updateContactFieldState('email', e.target.value);
  handleSubjectChange = e =>
    this.updateContactFieldState('subject', e.target.value);
  handleContentsChange = e =>
    this.updateContactFieldState('contents', e.target.value);

  hasError(section) {
    return (
      typeof this.props.form.errors[section] !== 'undefined' &&
      this.props.form.errors[section].length > 0
    );
  }

  renderError(section) {
    if (!this.hasError(section)) {
      return null;
    }

    const errors = this.props.form.errors[section].map(item => (
      <li key={item}>{item}</li>
    ));

    return <ul className="contact__error">{errors}</ul>;
  }

  renderSuccess() {
    if (this.props.success.length === 0) {
      return null;
    }

    return <div className="contact__success">{this.props.success[0]}</div>;
  }

  render() {
    return (
      <Fragment>
        <h3 id="contact-me">Let&apos;s Connect!</h3>
        {this.renderSuccess()}
        <form
          action="/about/"
          className="contact"
          method="post"
          name="anujnair_contact_form"
        >
          {this.renderError('name')}
          <label
            className={`contact__group ${
              this.hasError('name') ? 'contact__group--error' : ''
            }`}
            htmlFor="anujnair_contact_form[name]"
          >
            <span className="icon icon-man" />
            <input
              id="anujnair_contact_form[name]"
              name="anujnair_contact_form[name]"
              onChange={this.handleNameChange}
              placeholder="Name"
              type="text"
              value={this.state.name}
            />
          </label>

          {this.renderError('email')}
          <label
            className={`contact__group ${
              this.hasError('email') ? 'contact__group--error' : ''
            }`}
            htmlFor="anujnair_contact_form[email]"
          >
            <span className="icon icon-mail-full" />
            <input
              id="anujnair_contact_form[email]"
              name="anujnair_contact_form[email]"
              onChange={this.handleEmailChange}
              placeholder="Email"
              type="text"
              value={this.state.email}
            />
          </label>

          {this.renderError('subject')}
          <label
            className={`contact__group ${
              this.hasError('subject') ? 'contact__group--error' : ''
            }`}
            htmlFor="anujnair_contact_form[subject]"
          >
            <span className="icon icon-pencil-full" />
            <input
              id="anujnair_contact_form[subject]"
              name="anujnair_contact_form[subject]"
              onChange={this.handleSubjectChange}
              placeholder="Subject"
              type="text"
              value={this.state.subject}
            />
          </label>

          {this.renderError('contents')}
          <label
            className={`contact__group ${
              this.hasError('contents') ? 'contact__group--error' : ''
            }`}
            htmlFor="anujnair_contact_form[contents]"
          >
            <span className="icon icon-chats-full" />
            <textarea
              id="anujnair_contact_form[contents]"
              name="anujnair_contact_form[contents]"
              onChange={this.handleContentsChange}
              placeholder="Write message here"
              rows={4}
              value={this.state.contents}
            />
          </label>

          <input
            name="anujnair_contact_form[_token]"
            type="hidden"
            value={this.props.form.csrf}
          />

          <button
            className="contact__submit"
            name="anujnair_contact_form[send]"
            type="submit"
          >
            Send <span className="icon icon-plane" />
          </button>
        </form>
      </Fragment>
    );
  }
}
