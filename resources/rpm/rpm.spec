# SPEC file

%global c_vendor    %{_vendor}
%global gh_owner    %{_owner}
%global gh_project  %{_project}

Name:      %{_package}
Version:   %{_version}
Release:   %{_release}%{?dist}
Summary:   PHP library to process UTF-8 and Unicode text

License:   LGPLv3+
URL:       https://github.com/%{gh_owner}/%{gh_project}

BuildArch: noarch

Requires:  php(language) >= 8.2.0
Requires:  php-composer(%{c_vendor}/tc-lib-unicode-data) < 3.0.0
Requires:  php-composer(%{c_vendor}/tc-lib-unicode-data) >= 3.0.7
Requires:  php-ctype
Requires:  php-mbstring
Requires:  php-pcre

Provides:  php-composer(%{c_vendor}/%{gh_project}) = %{version}
Provides:  php-%{gh_project} = %{version}

%description
PHP library to process UTF-8 and Unicode text: conversions between UTF-8 strings, character arrays and code point arrays, the Unicode bidirectional algorithm (UAX #9) with Arabic shaping, and script-specific character substitution.

%build
#(cd %{_current_directory} && make build)

%install
rm -rf "%{buildroot}"
(cd "%{_current_directory}" && make install DESTDIR="%{buildroot}")

%files
%attr(-,root,root) %{_libpath}
%attr(-,root,root) %{_docpath}
%docdir %{_docpath}
# Optional config files can be listed here when used by a project.

%changelog
# The package version is defined by the VERSION and RELEASE files: see the
# Summary and Version fields above. This section lists the packaging changes.
* %{_builddate} Nicola Asuni <info@tecnick.com> %{version}-%{release}
- Refer to the project git history for the contents of this release.
* Tue Jul 21 2026 Nicola Asuni <info@tecnick.com> 1.0.0-1
- Initial Commit
