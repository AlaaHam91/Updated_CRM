<?php

namespace App\Providers;
use App\Http\Repositories\Eloquent\AcquaintanceMethodsRepository;
use App\Http\Repositories\Eloquent\AcquaintanceRepository;
use App\Http\Repositories\Eloquent\ActivityRepository;
use App\Http\Repositories\Eloquent\AdditionalFieldRepository;
use App\Http\Repositories\Eloquent\AddressConfigurationRepository;
use App\Http\Repositories\Eloquent\AdminDivisionRepository;
use App\Http\Repositories\Eloquent\AdminDivisionTypeRepository;
use App\Http\Repositories\Eloquent\AdministrationRepository;
use App\Http\Repositories\Eloquent\BranchRepository;
use App\Http\Repositories\Eloquent\CityRepository;
use App\Http\Repositories\Eloquent\CommunicationMethodRepository;
use App\Http\Repositories\Eloquent\CompanyPeopleRepository;
use App\Http\Repositories\Eloquent\CompanyRepository;
use App\Http\Repositories\Eloquent\ContactRepository;
use App\Http\Repositories\Eloquent\ContactSourcesRepository;
use App\Http\Repositories\Eloquent\CountryRepository;
use App\Http\Repositories\Eloquent\CurrencyRepository;
use App\Http\Repositories\Eloquent\CurrencyUnitRepository;
use App\Http\Repositories\Eloquent\DepartmentRepository;
use App\Http\Repositories\Eloquent\DocumentTypeRepository;
use App\Http\Repositories\Eloquent\EducationRepository;
use App\Http\Repositories\Eloquent\FileRepository;
use App\Http\Repositories\Eloquent\FileUploaderRepository;
use App\Http\Repositories\Eloquent\FinishTypeRepository;
use App\Http\Repositories\Eloquent\HierarchyRepository;
use App\Http\Repositories\Eloquent\InterestRepository;
use App\Http\Repositories\Eloquent\JobRepository;
use App\Http\Repositories\Eloquent\JobTypeRepository;
use App\Http\Repositories\Eloquent\LanguageRepository;
use App\Http\Repositories\Eloquent\NumberConfigurationRepository;
use App\Http\Repositories\Eloquent\NumberFieldRepository;
use App\Http\Repositories\Eloquent\PersonRepository;
use App\Http\Repositories\Eloquent\ProductRepository;
use App\Http\Repositories\Eloquent\ProjectRepository;
use App\Http\Repositories\Eloquent\SelectMenuRepository;
use App\Http\Repositories\Eloquent\SocialNetworkRepository;
use App\Http\Repositories\Eloquent\TextFieldRepository;
use App\Http\Repositories\Eloquent\UserRepository;
use App\Http\Repositories\IRepositories\IAcquaintanceMethodsRepository;
use App\Http\Repositories\IRepositories\IAcquaintanceRepository;
use App\Http\Repositories\IRepositories\IActivityRepository;
use App\Http\Repositories\IRepositories\IAdditionalFieldRepository;
use App\Http\Repositories\IRepositories\IAddressConfigurationRepository;
use App\Http\Repositories\IRepositories\IAdminDivisionRepository;
use App\Http\Repositories\IRepositories\IAdminDivisionTypeRepository;
use App\Http\Repositories\IRepositories\IAdministrationRepository;
use App\Http\Repositories\IRepositories\IBranchRepository;
use App\Http\Repositories\IRepositories\ICityRepository;
use App\Http\Repositories\IRepositories\ICommunicationMethodRepository;
use App\Http\Repositories\IRepositories\ICompanyPeopleRepository;
use App\Http\Repositories\IRepositories\ICompanyRepository;
use App\Http\Repositories\IRepositories\IContactRepository;
use App\Http\Repositories\IRepositories\IContactSourcesRepository;
use App\Http\Repositories\IRepositories\ICountryRepository;
use App\Http\Repositories\IRepositories\ICurrencyRepository;
use App\Http\Repositories\IRepositories\ICurrencyUnitRepository;
use App\Http\Repositories\IRepositories\IDepartmentRepository;
use App\Http\Repositories\IRepositories\IDocumentTypeRepository;
use App\Http\Repositories\IRepositories\IEducationRepository;
use App\Http\Repositories\IRepositories\IFileRepository;
use App\Http\Repositories\IRepositories\IFileUploaderRepository;
use App\Http\Repositories\IRepositories\IFinishTypeRepository;
use App\Http\Repositories\IRepositories\IHierarchyRepository;
use App\Http\Repositories\IRepositories\IInterestRepository;
use App\Http\Repositories\IRepositories\IJobRepository;
use App\Http\Repositories\IRepositories\IJobTypeRepository;
use App\Http\Repositories\IRepositories\ILanguageRepository;
use App\Http\Repositories\IRepositories\INumberConfigurationRepository;
use App\Http\Repositories\IRepositories\INumberFieldRepository;
use App\Http\Repositories\IRepositories\IPersonRepository;
use App\Http\Repositories\IRepositories\IProductRepository;
use App\Http\Repositories\IRepositories\IProjectRepository;
use App\Http\Repositories\IRepositories\ISelectMenuRepository;
use App\Http\Repositories\IRepositories\ISocialNetworkRepository;
use App\Http\Repositories\IRepositories\ITextFieldRepository;
use App\Http\Repositories\IRepositories\IUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ICityRepository::class,CityRepository::class);
        $this->app->bind(ICountryRepository::class,CountryRepository::class);
        $this->app->bind(IDepartmentRepository::class,DepartmentRepository::class);
        $this->app->bind(IInterestRepository::class,InterestRepository::class);
        $this->app->bind(IJobRepository::class,JobRepository::class);
        $this->app->bind(IJobTypeRepository::class,JobTypeRepository::class);
        $this->app->bind(ICurrencyRepository::class,CurrencyRepository::class);
        $this->app->bind(ICurrencyUnitRepository::class,CurrencyUnitRepository::class);
        $this->app->bind(ILanguageRepository::class,LanguageRepository::class);
        $this->app->bind(IActivityRepository::class,ActivityRepository::class);
        $this->app->bind(ISocialNetworkRepository::class,SocialNetworkRepository::class);
        $this->app->bind(IContactSourcesRepository::class,ContactSourcesRepository::class);
        $this->app->bind(IAcquaintanceMethodsRepository::class,AcquaintanceMethodsRepository::class);
        $this->app->bind(ICompanyRepository::class,CompanyRepository::class);
        $this->app->bind(IPersonRepository::class,PersonRepository::class);
        $this->app->bind(IAcquaintanceRepository::class,AcquaintanceRepository::class);
        $this->app->bind(ICompanyPeopleRepository::class,CompanyPeopleRepository::class);
        $this->app->bind(IContactRepository::class,ContactRepository::class);
        $this->app->bind(IAdminDivisionTypeRepository::class,AdminDivisionTypeRepository::class);
        $this->app->bind(IAdminDivisionRepository::class,AdminDivisionRepository::class);
        $this->app->bind(IFileRepository::class,FileRepository::class);
        $this->app->bind(IAdditionalFieldRepository::class,AdditionalFieldRepository::class);
        $this->app->bind(ITextFieldRepository::class,TextFieldRepository::class);
        $this->app->bind(INumberFieldRepository::class,NumberFieldRepository::class);
        $this->app->bind(ISelectMenuRepository::class,SelectMenuRepository::class);
        $this->app->bind(IFileUploaderRepository::class,FileUploaderRepository::class);
        $this->app->bind(IAdministrationRepository::class,AdministrationRepository::class);
        $this->app->bind(IBranchRepository::class,BranchRepository::class);
        $this->app->bind(IHierarchyRepository::class,HierarchyRepository::class);
        $this->app->bind(IEducationRepository::class,EducationRepository::class);
        $this->app->bind(IUserRepository::class,UserRepository::class);
        $this->app->bind(IProjectRepository::class,ProjectRepository::class);
        $this->app->bind(IFinishTypeRepository::class,FinishTypeRepository::class);
        $this->app->bind(IDocumentTypeRepository::class,DocumentTypeRepository::class);
        $this->app->bind(ICommunicationMethodRepository::class,CommunicationMethodRepository::class);
        $this->app->bind(IAddressConfigurationRepository::class,AddressConfigurationRepository::class);
        $this->app->bind(INumberConfigurationRepository::class,NumberConfigurationRepository::class);
        $this->app->bind(IProductRepository::class,ProductRepository::class);
        // $this->app->bind();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
