{ pkgs ? import <nixpkgs> {} }:

pkgs.mkShell {
  buildInputs = [
    pkgs.php81
    pkgs.composer
    # Add any other dependencies you need
  ];
}
