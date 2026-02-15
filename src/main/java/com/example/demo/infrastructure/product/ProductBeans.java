package com.example.demo.infrastructure.product;

import com.example.demo.data.repositories.ProductRepository;
import com.example.demo.domain.usecase.product.*;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

@Configuration
public class ProductBeans {

    @Bean
    public CreateProductUseCase createProductUseCase(ProductRepository productRepository) {
        ProductDatabaseGateway productDatabaseGateway = new ProductDatabaseGateway(productRepository);
        return new CreateProductUseCase(productDatabaseGateway);
    }

    @Bean
    public DeleteProductUseCase deleteProductUseCase(ProductRepository productRepository) {
        ProductDatabaseGateway productDatabaseGateway = new ProductDatabaseGateway(productRepository);
        return new DeleteProductUseCase(productDatabaseGateway);
    }

    @Bean
    public GetProductByCategoryUseCase getProductByCategoryUseCase(ProductRepository productRepository) {
        ProductDatabaseGateway productDatabaseGateway = new ProductDatabaseGateway(productRepository);
        return new GetProductByCategoryUseCase(productDatabaseGateway);
    }

    @Bean
    public GetProductByNameUseCase getProductByNameUseCase(ProductRepository productRepository) {
        ProductDatabaseGateway productDatabaseGateway = new ProductDatabaseGateway(productRepository);
        return new GetProductByNameUseCase(productDatabaseGateway);
    }

    @Bean
    public UpdateProductUseCase updateProductUseCase(ProductRepository productRepository) {
        ProductDatabaseGateway productDatabaseGateway = new ProductDatabaseGateway(productRepository);
        return new UpdateProductUseCase(productDatabaseGateway);
    }
}
