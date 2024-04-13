
import json 

from credentials import CredentialManager

if __name__ == "__main__":
    cred_manager = CredentialManager()
    print(repr(cred_manager.export_credentials()))
