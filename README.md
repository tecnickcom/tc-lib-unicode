# tc-lib-unicode

> UTF-8 and Unicode processing utilities, including bidirectional text handling.

[![Latest Stable Version](https://poser.pugx.org/tecnickcom/tc-lib-unicode/version)](https://packagist.org/packages/tecnickcom/tc-lib-unicode)
[![Build](https://github.com/tecnickcom/tc-lib-unicode/actions/workflows/check.yml/badge.svg)](https://github.com/tecnickcom/tc-lib-unicode/actions/workflows/check.yml)
[![Coverage](https://codecov.io/gh/tecnickcom/tc-lib-unicode/graph/badge.svg?token=XLM0QWY9BE)](https://codecov.io/gh/tecnickcom/tc-lib-unicode)
[![License](https://poser.pugx.org/tecnickcom/tc-lib-unicode/license)](https://packagist.org/packages/tecnickcom/tc-lib-unicode)
[![Downloads](https://poser.pugx.org/tecnickcom/tc-lib-unicode/downloads)](https://packagist.org/packages/tecnickcom/tc-lib-unicode)

[![Donate via PayPal](https://img.shields.io/badge/donate-paypal-87ceeb.svg)](https://www.paypal.com/donate/?hosted_button_id=NZUEC5XS8MFBJ)

If this library helps your multilingual stack, please consider [supporting development via PayPal](https://www.paypal.com/donate/?hosted_button_id=NZUEC5XS8MFBJ).

---

## Overview

`tc-lib-unicode` provides Unicode conversion helpers and bidirectional algorithm support for robust multilingual text processing.

| | |
|---|---|
| **Namespace** | `\Com\Tecnick\Unicode` |
| **Author** | Nicola Asuni <info@tecnick.com> |
| **License** | [GNU LGPL v3](https://www.gnu.org/copyleft/lesser.html) - see [LICENSE](LICENSE) |
| **API docs** | <https://tcpdf.org/docs/srcdoc/tc-lib-unicode> |
| **Packagist** | <https://packagist.org/packages/tecnickcom/tc-lib-unicode> |

---

## Features

### Unicode Utilities
- UTF-8 character and ordinal conversion helpers
- String/character array transformations
- Integration-ready conversion methods for document engines

### Bidirectional Support
- Unicode Bidirectional Algorithm implementation
- Right-to-left and mixed-direction text processing
- Supporting shaping/step logic for complex scripts

---

## Requirements

- PHP 8.1 or later
- Extensions: `mbstring`, `pcre`
- Composer

---

## Installation

```bash
composer require tecnickcom/tc-lib-unicode
```

---

## Quick Start

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$bidi = new \Com\Tecnick\Unicode\Bidi('hello ', null, null, 'R', false);
echo $bidi->getString();
```

---

## Development

```bash
make deps
make help
make qa
```

---

## Packaging

```bash
make rpm
make deb
```

For system packages, bootstrap with:

```php
require_once '/usr/share/php/Com/Tecnick/Unicode/autoload.php';
```

---

## Contributing

Contributions are welcome. Please review [CONTRIBUTING.md](CONTRIBUTING.md), [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md), and [SECURITY.md](SECURITY.md).

---

## Contact

Nicola Asuni - <info@tecnick.com>
